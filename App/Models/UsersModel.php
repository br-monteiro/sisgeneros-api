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
                        ->withAttribute('militaryOrganizations', function($e) {
                                return self::returnOmName($e->getId());
                            }, true)
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
                        ->withAttribute('militaryOrganizations', function($e) {
                                return self::returnOmName($e->getId());
                            })
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
                        "error" => Json::getValidateErrors()
                        ], 400);
            }

            $entity->setName($data->name);
            $entity->setFullName($data->fullName);
            $entity->setMilitaryPost($data->militaryPost);
            $entity->setNip($data->nip);
            $entity->setActive($data->active);

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

    private static function returnOmName(int $userId)
    {
        $query = ""
            . "SELECT mo.id, mo.name, "
            . "mo.naval_indicative as navalIndicative, "
            . "mo.is_ceim as isCeim, "
            . "uhmo.default, "
            . "uhmo.profile "
            . "FROM military_organizations AS mo "
            . "INNER JOIN users_has_military_organizations AS uhmo "
            . "ON uhmo.military_organizations_id = mo.id AND uhmo.users_id = $userId "
            . "ORDER BY mo.name";
        return db::em()
                ->getConnection()
                ->query($query)
                ->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function autocompleteOm(Request $request, Response $response): Response
    {
        $term = $request->getParam('query');
        $limit = (int) ($request->getParam('limit') ?? 50);
        $query = ""
            . "SELECT "
            . "    mo.id, "
            . "    mo.name, "
            . "    mo.naval_indicative as navalIndicative, "
            . "    mo.is_ceim as isCeim "
            . "FROM military_organizations AS mo "
            . "WHERE "
            . "    mo.name LIKE :term "
            . "    OR mo.naval_indicative LIKE :term "
            . "    OR mo.uasg_number LIKE :term "
            . "LIMIT {$limit}";

        $stmt = db::em()->getConnection() ->prepare($query);
        $stmt->execute([
            ':term' => '%' . $term . '%'
        ]);
        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);

        return $response->withJson([
            "message" => "Autocomplete for OMs of User",
            "status" => "success",
            "data" => $result
            ], 200);
    }

    /**
     * @param int $userId
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function allOmsFromUser(int $userId, Request $request, Response $response): Response
    {

        $result = self::returnOmName($userId);

        return $response->withJson([
            "message" => "All OMs by User",
            "status" => "success",
            "data" => $result
            ], 200);
    }

    /**
     * Update one register by ID
     * @param int $userId
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function saveProfile(int $userId, Request $request, Response $response): Response
    {
        $data = (object) $request->getParsedBody() ?? [];

        try {
            $repository = db::em()->getRepository(Users::class);
            $entity = $repository->find($userId);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "User not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'users_has_military_organizations_schema.json')) {
                return $response->withJson([
                        "message" => "There are wrong fields in submission",
                        "status" => "error",
                        "error" => Json::getValidateErrors()
                        ], 400);
            }

            $query = "INSERT INTO users_has_military_organizations VALUES (:userId, :omId, :profile, 'no')";
            $stmt = db::em()->getConnection()->prepare($query);
            $stmt->execute([
                ':userId' => $userId,
                ':omId' => $data->militaryOrganizationsId,
                ':profile' => $data->profile
            ]);

            $result = self::returnOmName($userId);

            return $response->withJson([
                    "message" => "Registry updated successfully",
                    "status" => "success",
                    "data" => $result,
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
     * Remove user profile
     * @param int $args
     * @param Response $response
     * @return Response
     */
    public static function removeProfile(array $args, Response $response): Response
    {
        try {

            $userId = (int) $args['userId'];
            $omId = (int) $args['omId'];

            $repository = db::em()->getRepository(Users::class);
            $entity = $repository->find($userId);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "User not found",
                            "status" => "error"
                            ], 404);
            }

            $query = "DELETE FROM users_has_military_organizations WHERE users_has_military_organizations.users_id = :userId AND users_has_military_organizations.military_organizations_id = :omId ";
            $stmt = db::em()->getConnection()->prepare($query);
            $stmt->execute([
                ':userId' => $userId,
                ':omId' => $omId,
            ]);

            return $response->withJson("", 204);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        }
    }

    /**
     * Change the default OM
     * @param int $args
     * @param Response $response
     * @return Response
     */
    public static function changeDefault(array $args, Request $request, Response $response): Response
    {
        try {

            $userId = (int) $args['userId'];
            $omId = (int) $args['omId'];

            $repository = db::em()->getRepository(Users::class);
            $entity = $repository->find($userId);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "User not found",
                            "status" => "error"
                            ], 404);
            }

            $query = "UPDATE users_has_military_organizations SET users_has_military_organizations.default = 'no'  WHERE users_has_military_organizations.users_id = :userId";
            $stmt = db::em()->getConnection()->prepare($query);
            $stmt->execute([
                ':userId' => $userId,
            ]);
            $query = "UPDATE users_has_military_organizations SET users_has_military_organizations.default = 'yes'  WHERE users_has_military_organizations.users_id = :userId AND users_has_military_organizations.military_organizations_id = :omId";
            $stmt = db::em()->getConnection()->prepare($query);
            $stmt->execute([
                ':userId' => $userId,
                ':omId' => $omId,
            ]);

            return self::allOmsFromUser($userId, $request, $response);

        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        }
    }
}
