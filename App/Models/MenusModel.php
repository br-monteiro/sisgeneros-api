<?php
namespace App\Models;

use HTR\Database\EntityAbstract as db;
use HTR\Database\AbstractModel;
use HTR\Common\Json;
use Slim\Http\Response;
use Slim\Http\Request;
use Doctrine\ORM\ORMException;
use App\Helpers\PaginatorHelper as paginator;
use App\Helpers\AuthenticationHelper as authentication;
use App\Exceptions\PaginatorException;
use App\Exceptions\AuthorizedMenuException;
use App\Exceptions\InvalidRangeDateException;
use App\Exceptions\DoubleRegistrationException;
use App\Entities\Menus;
use App\Entities\MilitaryOrganizations;
use App\Entities\Users;

class MenusModel extends AbstractModel
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

            $userProfile = authentication::getUserProfile($request);

            $paginator = paginator::buildAttributes($request, 'menus');
            $limit = $paginator->limit;
            $offset = $paginator->offset;
            // select according the military that user is logged in
            $where = ['militaryOrganizations' => $userProfile->militaryOrganizationId];
            $repository = db::em()->getRepository(Menus::class);
            $entity = $repository->findBy($where, ['beginning' => 'ASC'], $limit, $offset);

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
     * @param int $id The identify value
     * @param Response $response The Response Object
     * @return Response
     */
    public static function find(int $id, Response $response): Response
    {
        try {
            $repository = db::em()->getRepository(Menus::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Menu not found",
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
     * @param Request $request The Resquest Object
     * @param Response $response The Response Object
     * @return Response
     */
    public static function create(Request $request, Response $response): Response
    {
        $data = (object) $request->getParsedBody() ?? [];

        if (!self::inputValidate($data, 'menus_schema.json')) {
            return $response->withJson([
                    "message" => "There are wrong fields in submission",
                    "status" => "error",
                    "error" => Json::getValidateErrors()
                    ], 400);
        }

        try {

            $entity = self::buildEntityValues(new Menus(), $data);

            self::checkDoubleRegistration($entity);

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
        } catch (InvalidRangeDateException $ex) {
            return $response->withJson([
                    "message" => $ex->getMessage(),
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
        return $response;
        $data = (object) $request->getParsedBody() ?? [];

        try {
            $repository = db::em()->getRepository(Menus::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Menu not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'menus_schema.json')) {
                return $response->withJson([
                        "message" => "There are wrong fields in submission",
                        "status" => "error",
                        "error" => Json::getValidateErrors()
                        ], 400);
            }

            $entity = self::buildEntityValues($entity, $data);

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
        } catch (AuthorizedMenuException $ex) {
            return $response->withJson([
                    "message" => $ex->getMessage(),
                    "status" => "warning"
                    ], 400);
        } catch (InvalidRangeDateException $ex) {
            return $response->withJson([
                    "message" => $ex->getMessage(),
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
            $repository = db::em()->getRepository(Menus::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Menu not found",
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
     * Build the callbacks of output values
     * @return array
     */
    private static function buildCallbacks(): array
    {
        return [
            'beginning' => function($e) {
                return $e->getBeginning()->format('Y-m-d');
            },
            'ending' => function($e) {
                return $e->getEnding()->format('Y-m-d');
            },
            'militaryOrganizations' => function($e) {
                $obj = new \stdClass();
                $obj->id = $e->getMilitaryOrganizations()->getId();
                $obj->name = $e->getMilitaryOrganizations()->getName();
                return $obj;
            },
            'requesterUser' => function($e) {
                $obj = new \stdClass();
                $obj->id = $e->getRequesterUser()->getId();
                $obj->name = $e->getRequesterUser()->getFullName();
                return $obj;
            },
            'authorizerUser' => function($e) {
                $obj = new \stdClass();
                if ($e->getStatus() != 'created') {
                    $obj->id = $e->getAuthorizerUser()->getId();
                    $obj->name = $e->getAuthorizerUser()->getFullName();
                }
                return $obj;
            }
        ];
    }

    /**
     * Build the Menus values
     * @param Menus $entity
     * @param \stdClass $data
     * @throws AuthorizedMenuException
     */
    private static function buildEntityValues(Menus $entity, \stdClass $data): Menus
    {
        if ($entity->getStatus() == 'authorized') {
            throw new AuthorizedMenuException("This menu has been authorized");
        }
        // validate the range of date
        $beginning = new \DateTime($data->beginning);
        $ending = new \DateTime($data->ending);
        if (($ending->getTimestamp() - $beginning->getTimestamp()) !== 518400) {
            throw new InvalidRangeDateException("The start date and end date should be one week");
        }
        // users
        $repositoryUsers = db::em()->getRepository(Users::class);
        $status = 'created';

        if (!$entity->getId()) {
            $requesterUser = $repositoryUsers->find($data->requesterUser);
            $authorizerUser = $requesterUser;
            // military organizations
            $militaryOrganizations = db::em()
                ->getRepository(MilitaryOrganizations::class)
                ->find($data->militaryOrganizationsId);
            $entity->setMilitaryOrganizations($militaryOrganizations);
        } else {
            $requesterUser = $entity->getRequesterUser();
            $authorizerUser = $entity->getAuthorizerUser();
            $status = $entity->getStatus();
        }

        if ($data->status == 'authorized' && $entity->getStatus() == 'created') {
            $status = 'authorized';
            $authorizerUser = $repositoryUsers->find($data->authorizerUser);
        }

        $entity->setRequesterUser($requesterUser);
        $entity->setAuthorizerUser($authorizerUser);
        $entity->setStatus($status);
        $entity->setBeginning($beginning);
        $entity->setEnding($ending);
        // return
        return $entity;
    }

    /**
     * Checks if there is a record with the same data
     * @param Menus $entity
     * @throws DoubleRegistrationException
     */
    private static function checkDoubleRegistration(Menus $entity)
    {
        $menuRepository = db::em()
            ->getRepository(Menus::class)
            ->findBy([
            'militaryOrganizations' => $entity->getMilitaryOrganizations(),
            'beginning' => $entity->getBeginning(),
            'ending' => $entity->getEnding()
        ]);

        if ($menuRepository) {
            throw new DoubleRegistrationException("A record with this data already exists");
        }
    }
}
