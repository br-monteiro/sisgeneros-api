<?php
namespace App\Models;

use HTR\Database\EntityAbstract as db;
use HTR\Database\AbstractModel;
use HTR\Common\Json;
use Slim\Http\Response;
use Slim\Http\Request;
use Doctrine\ORM\ORMException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\System\Configuration as cfg;
use App\Helpers\PaginatorHelper as paginator;
use App\Exceptions\PaginatorException;
use App\Entities\Users;
use App\Entities\Auth;

class UsersModel extends AbstractModel
{

    /**
     * Returns all register
     * @param Response $response
     * @return Response
     */
    public static function findAll(Request $request, Response $response): Response
    {
        try {

            $paginator = paginator::buildAttributes($request, 'users');

            if ($paginator->hasError) {
                throw new PaginatorException($paginator->error);
            }

            $limit = $paginator->limit;
            $offset = $paginator->offset;
            $repository = db::em()->getRepository(Users::class);
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
     * Return one register by ID
     * @param int $id
     * @param Response $response
     * @return Response
     */
    public static function find(int $id, Response $response): Response
    {
        try {
            $repository = db::em()->getRepository(Users::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "User not found",
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
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function create(Request $request, Response $response): Response
    {
        $data = (object) $request->getParsedBody() ?? [];

        if (
            !self::inputValidate($data, 'users_schema.json') ||
            !self::inputValidate($data, 'auth_schema.json')
        ) {
            return $response->withJson([
                    "message" => "There are wrong fields in submission",
                    "status" => "error",
                    "error" => Json::getValidateErrors()
                    ], 400);
        }

        db::em()->getConnection()->beginTransaction();

        try {
            $entity = new Users();
            $entity->setName($data->name);
            $entity->setFullName($data->fullName);
            $entity->setMilitaryPost($data->militaryPost);
            $entity->setNip($data->nip);
            $entity->setIsMaster('no');
            $entity->setActive('yes');
            db::em()->persist($entity);
            // flush transaction
            db::em()->flush();

            // datetime
            $datetime = new \DateTime('now');
            $datetime->modify('+3 month');
            // auth model
            $entityAuth = new Auth();
            $entityAuth->setPassword(password_hash($data->password . cfg::SALT_KEY, PASSWORD_DEFAULT));
            $entityAuth->setUsername(md5($data->username));
            $entityAuth->setUsers($entity);
            $entityAuth->setValidate($datetime);
            db::em()->persist($entityAuth);
            // flush transaction
            db::em()->flush();
            // commit transaction
            db::em()->getConnection()->commit();

            return $response->withJson([
                    "message" => "Registry created successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute('militaryOrganizations')
                        ->run()
                    ], 201);
        } catch (ORMException $ex) {
            db::em()->getConnection()->rollBack();
            return self::commonError($response, $ex);
        } catch (UniqueConstraintViolationException $ex) {
            db::em()->getConnection()->rollBack();
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
            $repository = db::em()->getRepository(Users::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "User not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'users_schema.json')) {
                return $response->withJson([
                        "message" => "There are wrong fields in submission",
                        "status" => "error",
                        "details" => Json::getValidateErrors()
                        ], 400);
            }

            $entity->setName($data->name);
            $entity->setFullName($data->fullName);
            $entity->setMilitaryPost($data->militaryPost);
            $entity->setNip($data->nip);

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
            $repository = db::em()->getRepository(Users::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "User not found",
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
