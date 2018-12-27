<?php
namespace App\Models;

use HTR\Database\EntityAbstract as db;
use HTR\Database\AbstractModel;
use HTR\Common\Json;
use Slim\Http\Response;
use Slim\Http\Request;
use Doctrine\ORM\ORMException;
use App\Helpers\PaginatorHelper as paginator;
use App\Exceptions\PaginatorException;
use App\Exceptions\InvalidDateException;
use App\Exceptions\DoubleRegistrationException;
use App\Entities\BiddingsItems;
use App\Entities\Biddings;
use App\Entities\Suppliers;

class BiddingsItemsModel extends AbstractModel
{

    /**
     * @param array $args
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function findAll(array $args, Request $request, Response $response): Response
    {
        try {
            $paginator = paginator::buildAttributes($request, 'biddings_items');
            $limit = $paginator->limit;
            $offset = $paginator->offset;
            $repository = db::em()->getRepository(BiddingsItems::class);
            $criteria = [];
            // search all by Biddings ID
            if (isset($args['biddingId'])) {
                $criteria = ['biddings' => $args['biddingId']];
            }

            $entity = $repository->findBy($criteria, ['number' => 'ASC'], $limit, $offset);

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "allResults" => $paginator->allResults,
                    "limit" => $limit,
                    "offset" => $offset,
                    "page" => $paginator->page,
                    "data" => self::outputValidate($entity)
                        ->withAttribute(self::buildCallbacks(), null, true)
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        } catch (PaginatorException $ex) {
            return self::commonError($response, $ex);
        }
    }

    /**
     * Return one register by ID
     * @param int $id
     * @param Response $response
     * @return Response
     */
    public static function find(int $id, Response $response): Response
    {
        try {
            $repository = db::em()->getRepository(BiddingsItems::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Item not found",
                            "status" => "error"
                            ], 404);
            }

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withAttribute(self::buildCallbacks())
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        }
    }

    /**
     * Register new value
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function create(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody()->getContents() ?? []);

        if (!self::inputValidate($data, 'biddings_items_schema.json')) {
            return $response->withJson([
                    "message" => "There are wrong fields in submission",
                    "status" => "error",
                    "error" => Json::getValidateErrors()
                    ], 400);
        }

        try {

            $biddings = db::em()
                ->getRepository(Biddings::class)
                ->find($data->biddingsId);

            $suppliers = db::em()
                ->getRepository(Suppliers::class)
                ->find($data->suppliersId);

            // validate to not save a double register
            self::checkDoubleRegistration($data);

            $entity = new BiddingsItems();
            $entity->setBiddings($biddings);
            $entity->setSuppliers($suppliers);
            $entity->setNumber($data->number);
            $entity->setName($data->name);
            $entity->setSupplyUnit($data->supplyUnit);
            $entity->setInitialQuantity($data->initialQuantity);
            $entity->setCurrentQuantity($data->initialQuantity);
            $entity->setValue($data->value);

            db::em()->persist($entity);
            // flush transaction
            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry created successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withAttribute(self::buildCallbacks())
                        ->run()
                    ], 201);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        } catch (DoubleRegistrationException $ex) {
            return $response->withJson([
                    "message" => $ex->getMessage(),
                    "status" => "error"
                    ], 400);
        }
    }

    /**
     * Update one register by ID
     * @param int $id
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function update(int $id, Request $request, Response $response): Response
    {
        $data = (object) $request->getParsedBody() ?? [];

        try {
            $repository = db::em()->getRepository(BiddingsItems::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Item not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'biddings_items_schema.json')) {
                return $response->withJson([
                        "message" => "There are wrong fields in submission",
                        "status" => "error",
                        "error" => Json::getValidateErrors()
                        ], 400);
            }

            $biddings = db::em()
                ->getRepository(Biddings::class)
                ->find($data->biddingsId);

            $suppliers = db::em()
                ->getRepository(Suppliers::class)
                ->find($data->suppliersId);

            // validate to not save a double register
            self::checkDoubleRegistration($data, $id);

            $entity->setBiddings($biddings);
            $entity->setSuppliers($suppliers);
            $entity->setNumber($data->number);
            $entity->setName($data->name);
            $entity->setSupplyUnit($data->supplyUnit);
            $entity->setInitialQuantity($data->initialQuantity);
            $entity->setCurrentQuantity($data->initialQuantity);
            $entity->setValue($data->value);

            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry updated successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withAttribute(self::buildCallbacks())
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        } catch (InvalidDateException $ex) {
            return $response->withJson([
                    "message" => $ex->getMessage(),
                    "status" => "error"
                    ], 400);
        } catch (DoubleRegistrationException $ex) {
            return $response->withJson([
                    "message" => $ex->getMessage(),
                    "status" => "error"
                    ], 400);
        }
    }

    /**
     * Remove one register by ID
     * @param int $id
     * @param Response $response
     * @return Response
     */
    public static function remove(int $id, Response $response): Response
    {
        try {
            $repository = db::em()->getRepository(BiddingsItems::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Item not found",
                            "status" => "error"
                            ], 404);
            }

            db::em()->remove($entity);
            db::em()->flush();

            return $response->withJson("", 204);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        }
    }

    private static function buildCallbacks(): array
    {
        return [
            'biddings' => function ($e) {
                $obj = new \stdClass();
                $obj->id = $e->getBiddings()->getId();
                $obj->number = $e->getBiddings()->getNumber() . '/' . $e->getBiddings()->getYear();
                return $obj;
            },
            'suppliers' => function ($e) {
                $obj = new \stdClass();
                $obj->id = $e->getSuppliers()->getId();
                $obj->name = $e->getSuppliers()->getName();
                return $obj;
            }
        ];
    }

    /**
     * Checks if there is a record with the same data
     * @param \stdClass $data
     * @throws DoubleRegistrationException
     */
    private static function checkDoubleRegistration(\stdClass $data, int $id = 0)
    {
        $query = ""
            . "SELECT "
            . "    bi.id "
            . "FROM "
            . "    biddings_items AS bi "
            . "WHERE "
            . "    bi.biddings_id = :bid "
            . "    AND (bi.name = :name OR bi.number = :number) ";

        $param = [
            ":bid" => $data->biddingsId ?? time(),
            ":name" => $data->name ?? time(),
            ":number" => $data->number ?? time()
        ];

        if ($id) {
            $query .= " AND bi.id != :id";
            $param[':id'] = $id;
        }

        $stmt = db::em()->getConnection()->prepare($query);
        $stmt->execute($param);

        if ($stmt->rowCount() > 0) {
            throw new DoubleRegistrationException("A record with this data already exists");
        }
    }

    /**
     * @param array $args
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function search(array $args, Request $request, Response $response): Response
    {
        try {
            $biddingId = $args['biddingId'] ?? 0;
            $term = $request->getParam('query', time());
            $paginator = paginator::buildAttributes($request, 'biddings_items', 'id', 'WHERE biddings_id = ' . $biddingId);
            $limit = $paginator->limit;
            $offset = $paginator->offset;
            $repository = db::em()->getRepository(BiddingsItems::class);

            $query = $repository->createQueryBuilder('bd')
                ->where('bd.biddings = :bidding')
                ->andWhere('bd.number LIKE :number OR bd.name LIKE :name')
                ->setParameter('bidding', $biddingId)
                ->setParameter('number', '%' . $term . '%')
                ->setParameter('name', '%' . $term . '%')
                ->add('orderBy', 'bd.number ASC')
                ->setMaxResults($limit)
                ->setFirstResult($offset)
                ->getQuery();
            $entity = $query->getResult();

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "allResults" => $paginator->allResults,
                    "limit" => $limit,
                    "offset" => $offset,
                    "data" => self::outputValidate($entity)
                        ->withAttribute(self::buildCallbacks(), null, true)
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        } catch (PaginatorException $ex) {
            return self::commonError($response, $ex);
        }
    }
}
