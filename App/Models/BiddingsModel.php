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
use App\Entities\Biddings;

class BiddingsModel extends AbstractModel
{

    /**
     * Returns all register
     * @param Response $response
     * @return Response
     */
    public static function findAll(Request $request, Response $response): Response
    {
        try {

            $paginator = paginator::buildAttributes($request, 'biddings');
            $limit = $paginator->limit;
            $offset = $paginator->offset;
            $repository = db::em()->getRepository(Biddings::class);
            $entity = $repository->findBy([], ['year' => 'DESC', 'number' => 'ASC'], $limit, $offset);

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "allResults" => $paginator->allResults,
                    "limit" => $limit,
                    "offset" => $offset,
                    "page" => $paginator->page,
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute('militaryOrganizations')
                        ->withAttribute([
                            'validate' => function ($e) {
                                return $e->getValidate()->format('Y-m-d');
                            }
                            ], null, true)
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
            $repository = db::em()->getRepository(Biddings::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Bidding not found",
                            "status" => "error"
                            ], 404);
            }

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute('militaryOrganizations')
                        ->withAttribute([
                            'validate' => function ($e) {
                                return $e->getValidate()->format('Y-m-d');
                            }
                        ])
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
        $data = (object) $request->getParsedBody() ?? [];

        if (!self::inputValidate($data, 'biddings_schema.json')) {
            return $response->withJson([
                    "message" => "There are wrong fields in submission",
                    "status" => "error",
                    "error" => Json::getValidateErrors()
                    ], 400);
        }

        try {

            // validate to not save a double register
            self::checkDoubleRegistration($data);
            // check if the validate attribute is valid
            $dateValidate = self::validateYear($data);

            $entity = new Biddings();
            $entity->setNumber($data->number);
            $entity->setYear($data->year);
            $entity->setUasgNumber($data->uasgNumber);
            $entity->setUasgName($data->uasgName);
            $entity->setValidate($dateValidate);

            db::em()->persist($entity);
            // flush transaction
            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry created successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute('militaryOrganizations')
                        ->withAttribute([
                            'validate' => function ($e) {
                                return $e->getValidate()->format('Y-m-d');
                            }
                        ])
                        ->run()
                    ], 201);
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
            $repository = db::em()->getRepository(Biddings::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Bidding not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'biddings_schema.json')) {
                return $response->withJson([
                        "message" => "There are wrong fields in submission",
                        "status" => "error",
                        "error" => Json::getValidateErrors()
                        ], 400);
            }

            // validate to not save a double register
            self::checkDoubleRegistration($data, $id);
            // check if the validate attribute is valid
            $dateValidate = self::validateYear($data);

            $entity->setNumber($data->number);
            $entity->setYear($data->year);
            $entity->setUasgNumber($data->uasgNumber);
            $entity->setUasgName($data->uasgName);
            $entity->setValidate($dateValidate);

            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry updated successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute('militaryOrganizations')
                        ->withAttribute([
                            'validate' => function ($e) {
                                return $e->getValidate()->format('Y-m-d');
                            }
                        ])
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
            $repository = db::em()->getRepository(Biddings::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Bidding not found",
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
     * Validate the reported year
     * @param \stdClass $data
     * @return \DateTime
     * @throws InvalidDateException
     */
    private static function validateYear(\stdClass $data): \DateTime
    {
        /**
         * Valida the year reported
         */
        $dateValidate = new \DateTime($data->validate);
        if (2018 > $dateValidate->format("Y")) {
            throw new InvalidDateException("The reported year can not be less than 2018");
        }
        return $dateValidate;
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
            . "    b.id "
            . "FROM "
            . "    biddings AS b "
            . "WHERE "
            . "    b.number = :n "
            . "    AND (b.year = :y OR b.uasg_number = :un) ";
        $param = [
            ":n" => $data->number ?? time(),
            ":y" => $data->year ?? time(),
            ":un" => $data->uasgNumber ?? time()
        ];

        if ($id) {
            $query .= " AND b.id != :id";
            $param[':id'] = $id;
        }

        $stmt = db::em()->getConnection()->prepare($query);
        $stmt->execute($param);

        if ($stmt->rowCount() > 0) {
            throw new DoubleRegistrationException("A record with this data already exists");
        }
    }
}
