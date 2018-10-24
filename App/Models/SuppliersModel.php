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
use App\Entities\Suppliers;

class SuppliersModel extends AbstractModel
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

            $paginator = paginator::buildAttributes($request, 'suppliers');
            $limit = $paginator->limit;
            $offset = $paginator->offset;
            $repository = db::em()->getRepository(Suppliers::class);
            $entity = $repository->findBy([], null, $limit, $offset);

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "allResults" => $paginator->allResults,
                    "limit" => $limit,
                    "offset" => $offset,
                    "data" => self::outputValidate($entity)
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
            $repository = db::em()->getRepository(Suppliers::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Supplier not found",
                            "status" => "error"
                            ], 404);
            }

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
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

        if (!self::inputValidate($data, 'suppliers_schema.json')) {
            return $response->withJson([
                    "message" => "There are wrong fields in submission",
                    "status" => "error",
                    "error" => Json::getValidateErrors()
                    ], 400);
        }

        try {

            $entity = new Suppliers();
            $entity->setName($data->name);
            $entity->setCnpj($data->cnpj);
            $entity->setContacts($data->contacts);

            db::em()->persist($entity);
            // flush transaction
            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry created successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->run()
                    ], 201);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        } catch (UniqueConstraintViolationException $ex) {
            return $response->withJson([
                    "message" => $ex->getPrevious()->getMessage(),
                    "status" => "warning"
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
            $repository = db::em()->getRepository(Suppliers::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Suppliers not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'suppliers_schema.json')) {
                return $response->withJson([
                        "message" => "There are wrong fields in submission",
                        "status" => "error",
                        "error" => Json::getValidateErrors()
                        ], 400);
            }

            $entity->setName($data->name);
            $entity->setCnpj($data->cnpj);
            $entity->setContacts($data->contacts);

            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry updated successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        } catch (UniqueConstraintViolationException $ex) {
            return $response->withJson([
                    "message" => $ex->getPrevious()->getMessage(),
                    "status" => "warning"
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
            $repository = db::em()->getRepository(Suppliers::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Suppliers not found",
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
}
