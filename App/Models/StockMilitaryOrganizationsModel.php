<?php
namespace App\Models;

use HTR\Database\EntityAbstract as db;
use HTR\Database\AbstractModel;
use HTR\Common\Json;
use Slim\Http\Response;
use Slim\Http\Request;
use Doctrine\ORM\ORMException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Helpers\PaginatorHelper as paginator;
use App\Exceptions\PaginatorException;
use App\Exceptions\DoubleRegistrationException;
use App\Entities\MilitaryOrganizations;
use App\Entities\StockMilitaryOrganizations;

class StockMilitaryOrganizationsModel extends AbstractModel
{

    /**
     * Returns all register
     * @param Request $request The Resquest Object
     * @param Response $response The Response Object
     * @return Response
     */
    public static function findAll(Request $request, Response $response): Response
    {
        try {

            $paginator = paginator::buildAttributes($request, 'stock_military_organizations');
            $limit = $paginator->limit;
            $offset = $paginator->offset;
            $repository = db::em()->getRepository(StockMilitaryOrganizations::class);
            $entity = $repository->findBy([], null, $limit, $offset);

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "allResults" => $paginator->allResults,
                    "limit" => $limit,
                    "offset" => $offset,
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute('militaryOrganizations')
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        } catch (PaginatorException $ex) {
            return self::commonError($response, $ex);
        }
    }

    /**
     * Returns all register
     * @param Request $request The Resquest Object
     * @param Response $response The Response Object
     * @return Response
     */
    public static function findAllByMilitaryOrganizationsId(Request $request, Response $response): Response
    {
        try {

            $omId = $request->getAttribute('omId');
            $itemName = $request->getParam('itemName');
            $ceim = $request->getParam('ceim') === 'yes';

            $repository = db::em()->getRepository(StockMilitaryOrganizations::class);

            // URI /sisgeneros/api/v1/stockmilitaryorganizations/om/2?itemName=outro
            if ($omId && $itemName && !$ceim) {
                $entity = $repository->createQueryBuilder('smo')
                    ->where('smo.militaryOrganizations = :omId')
                    ->andWhere('smo.name = :itemName')
                    ->setParameter('omId', $omId)
                    ->setParameter('itemName', $itemName)
                    ->orderBy('smo.name')
                    ->getQuery()
                    ->getResult();
            // URI /sisgeneros/api/v1/stockmilitaryorganizations/om?itemName=outro&ceim=yes
            } elseif (!$omId && $itemName && $ceim) {
                $entity = $repository->createQueryBuilder('smo')
                    ->innerJoin('smo.militaryOrganizations', 'mo', 'WITH', 'mo.isCeim = :ceim')
                    ->where('smo.name = :itemName')
                    ->setParameter('itemName', $itemName)
                    ->setParameter('ceim', 'yes')
                    ->orderBy('smo.name')
                    ->getQuery()
                    ->getResult();
            // URI /sisgeneros/api/v1/stockmilitaryorganizations/om/1
            } elseif ($omId && !$itemName && !$ceim) {
                $entity = $repository->findBy(['militaryOrganizations' => $omId]);
            } else {
                return $response
                        ->withJson([
                            "message" => "Route not found",
                            "status" => "error"
                            ], 404);
            }

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute('militaryOrganizations')
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
     * @param int $id The identify value
     * @param Response $response The Response Object
     * @return Response
     */
    public static function find(int $id, Response $response): Response
    {
        try {
            $repository = db::em()->getRepository(StockMilitaryOrganizations::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Stock not found",
                            "status" => "error"
                            ], 404);
            }

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute('militaryOrganizations')
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        }
    }

    /**
     * Register new value
     * @param Request $request The Resquest Object
     * @param Response $response The Response Object
     * @return Response
     */
    public static function create(Request $request, Response $response): Response
    {
        $data = (object) $request->getParsedBody() ?? [];

        if (!self::inputValidate($data, 'stock_military_organizations_schema.json')) {
            return $response->withJson([
                    "message" => "There are wrong fields in submission",
                    "status" => "error",
                    "error" => Json::getValidateErrors()
                    ], 400);
        }

        try {

            self::checkDoubleRegistration($data);

            $militaryOrganizationsRepository = db::em()->getRepository(MilitaryOrganizations::class);
            $entity = new StockMilitaryOrganizations();
            $entity->setName($data->name);
            $entity->setSupplyUnit($data->supplyUnit);
            $entity->setQuantity($data->quantity);
            $entity->setMilitaryOrganizations($militaryOrganizationsRepository->find($data->militaryOrganizationsId));

            db::em()->persist($entity);
            // flush transaction
            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry created successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute('militaryOrganizations')
                        ->run()
                    ], 201);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        } catch (UniqueConstraintViolationException $ex) {
            return $response->withJson([
                    "message" => $ex->getPrevious()->getMessage(),
                    "status" => "warning"
                    ], 400);
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
            $repository = db::em()->getRepository(StockMilitaryOrganizations::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Stock not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'stock_military_organizations_schema.json')) {
                return $response->withJson([
                        "message" => "There are wrong fields in submission",
                        "status" => "error",
                        "error" => Json::getValidateErrors()
                        ], 400);
            }


            self::checkDoubleRegistration($data, $id);

            $militaryOrganizationsRepository = db::em()->getRepository(MilitaryOrganizations::class);
            $entity->setName($data->name);
            $entity->setSupplyUnit($data->supplyUnit);
            $entity->setQuantity($data->quantity);
            $entity->setMilitaryOrganizations($militaryOrganizationsRepository->find($data->militaryOrganizationsId));

            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry updated successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute('militaryOrganizations')
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        } catch (UniqueConstraintViolationException $ex) {
            return $response->withJson([
                    "message" => $ex->getPrevious()->getMessage(),
                    "status" => "warning"
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
            $repository = db::em()->getRepository(StockMilitaryOrganizations::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Stock not found",
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

    /**
     * Checks if there is a record with the same data
     * @param \stdClass $data
     * @throws DoubleRegistrationException
     */
    private static function checkDoubleRegistration(\stdClass $data, int $id = 0)
    {
        $query = ""
            . "SELECT "
            . "    smo.id "
            . "FROM "
            . "    stock_military_organizations AS smo "
            . "WHERE "
            . "    smo.military_organizations_id = :moi "
            . "    AND smo.name = :name ";
        $param = [
            ":moi" => $data->militaryOrganizationsId ?? time(),
            ":name" => $data->name ?? time()
        ];

        if ($id) {
            $query .= " AND smo.id != :id";
            $param[':id'] = $id;
        }

        $stmt = db::em()->getConnection()->prepare($query);
        $stmt->execute($param);
        if ($stmt->rowCount() > 0) {
            throw new DoubleRegistrationException("A record with this data already exists");
        }
    }
}
