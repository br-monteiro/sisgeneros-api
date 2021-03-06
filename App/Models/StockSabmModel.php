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
use App\Entities\StockSabm;

class StockSabmModel extends AbstractModel
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

            $paginator = paginator::buildAttributes($request, 'stock_sabm');
            $limit = $paginator->limit;
            $offset = $paginator->offset;

            $entity = self::rawEntitiesByItemName(null, $limit, $offset);

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "allResults" => $paginator->allResults,
                    "limit" => $limit,
                    "offset" => $offset,
                    "page" => $paginator->page,
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
            $repository = db::em()->getRepository(StockSabm::class);
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

        if (!self::inputValidate($data, 'stock_sabm_schema.json')) {
            return $response->withJson([
                    "message" => "There are wrong fields in submission",
                    "status" => "error",
                    "error" => Json::getValidateErrors()
                    ], 400);
        }

        try {

            self::checkDoubleRegistration($data);

            $militaryOrganizationsRepository = db::em()->getRepository(MilitaryOrganizations::class);
            $entity = new StockSabm();
            $entity->setName($data->name);
            $entity->setSupplyUnit($data->supplyUnit);
            $entity->setQuantity($data->quantity);
            $entity->setPiIdentifier($data->piIdentifier);
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
            $repository = db::em()->getRepository(StockSabm::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Stock not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'stock_sabm_schema.json')) {
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
            $entity->setPiIdentifier($data->piIdentifier);
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
            $repository = db::em()->getRepository(StockSabm::class);
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
            . "    ss.id "
            . "FROM "
            . "    stock_sabm AS ss "
            . "WHERE "
            . "    ss.military_organizations_id = :moi"
            . "    AND (ss.name = :name OR ss.pi_identifier = :pi )";
        $param = [
            ":moi" => $data->militaryOrganizationsId ?? time(),
            ":name" => $data->name ?? time(),
            ":pi" => $data->piIdentifier ?? time()
        ];

        if ($id) {
            $query .= " AND ss.id != :id";
            $param[':id'] = $id;
        }

        $stmt = db::em()->getConnection()->prepare($query);
        $stmt->execute($param);
        if ($stmt->rowCount() > 0) {
            throw new DoubleRegistrationException("A record with this data already exists");
        }
    }

    /**
     * 
     * @param string $itemName
     * @param int $limit
     * @param int $offset
     * @return StockSabm
     */
    public static function rawEntitiesByItemName($itemName = null, $limit = null, $offset = null)
    {
        $where = [];
        if ($itemName) {
            $where = ['name' => $itemName];
        }
        $repository = db::em()->getRepository(StockSabm::class);
        return $repository->findBy($where, null, $limit, $offset);
    }
}
