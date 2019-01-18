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
use App\Entities\MenuDays;
use App\Entities\Menus;
use App\Entities\Meals;
use App\Entities\Recipes;

class MenuDaysModel extends AbstractModel
{

    /**
     * Returns all register
     * @param array $args
     * @param Request $request The Resquest Object
     * @param Response $response The Response Object
     * @return Response
     */
    public static function findAll(array $args, Request $request, Response $response): Response
    {
        try {

            $paginator = paginator::buildAttributes($request, 'menu_days');
            $limit = $paginator->limit;
            $offset = $paginator->offset;
            $repository = db::em()->getRepository(MenuDays::class);
            $recipesRepository = db::em()->getRepository(Recipes::class);

            $queryBuild = $repository->createQueryBuilder('md')
                ->innerJoin('md.meals', 'ml', 'WITH', 'ml.id = md.meals');

            if (isset($args['menuId'])) {
                $queryBuild = $queryBuild->where('md.menus = :menuId')
                    ->setParameter(':menuId', $args['menuId']);
            }

            $entity = $queryBuild
                ->orderBy('ml.sort')
                ->getQuery()
                ->getResult();


            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "allResults" => $paginator->allResults,
                    "limit" => $limit,
                    "offset" => $offset,
                    "page" => $paginator->page,
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute(['menus', 'meals'])
                        ->withAttribute([
                            'date' => function ($e) {
                                return $e->getDate()->format('Y-m-d');
                            },
                            'meal' => function ($e) {
                                $obj = new \stdClass();
                                $obj->sort = $e->getMeals()->getSort();
                                $obj->name = $e->getMeals()->getName();
                                return $obj;
                            },
                            'recipe' => function ($e) use ($recipesRepository) {
                                $menuDayId = $e->getId();
                                $recipe = $recipesRepository->findOneBy(['menuDays' => $menuDayId]);
                                if ($recipe) {
                                    return $recipe->getName();
                                }
                                return '';
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
     * @param int $id The identify value
     * @param Response $response The Response Object
     * @return Response
     */
    public static function find(int $id, Response $response): Response
    {
        try {
            $repository = db::em()->getRepository(MenuDays::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Menu Day not found",
                            "status" => "error"
                            ], 404);
            }

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute(['menus', 'meals'])
                        ->withAttribute([
                            'date' => function ($e) {
                                return $e->getDate()->format('Y-m-d');
                            },
                            'meal' => function ($e) {
                                return $e->getMeals()->getName();
                            },
                        ])
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

        if (!self::inputValidate($data, 'menu_days_schema.json')) {
            return $response->withJson([
                    "message" => "There are wrong fields in submission",
                    "status" => "error",
                    "error" => Json::getValidateErrors()
                    ], 400);
        }

        try {

            $menus = db::em()->getRepository(Menus::class)->find($data->menusId);
            $meals = db::em()->getRepository(Meals::class)->find($data->mealsId);

            $entity = new MenuDays();
            $entity->setDate(new \DateTime($data->date));
            $entity->setQuantityPeople($data->quantityPeople);
            $entity->setMenus($menus);
            $entity->setMeals($meals);

            db::em()->persist($entity);
            // flush transaction
            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry created successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute(['menus', 'meals'])
                        ->withAttribute([
                            'date' => function ($e) {
                                return $e->getDate()->format('Y-m-d');
                            },
                            'meal' => function ($e) {
                                return $e->getMeals()->getName();
                            },
                        ])
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

            $repository = db::em()->getRepository(MenuDays::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Menu Day not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'menu_days_schema.json')) {
                return $response->withJson([
                        "message" => "There are wrong fields in submission",
                        "status" => "error",
                        "error" => Json::getValidateErrors()
                        ], 400);
            }

            $menus = db::em()->getRepository(Menus::class)->find($data->menusId);
            $meals = db::em()->getRepository(Meals::class)->find($data->mealsId);

            $entity->setDate(new \DateTime($data->date));
            $entity->setQuantityPeople($data->quantityPeople);
            $entity->setMenus($menus);
            $entity->setMeals($meals);

            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry updated successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute(['menus', 'meals'])
                        ->withAttribute([
                            'date' => function ($e) {
                                return $e->getDate()->format('Y-m-d');
                            },
                            'meal' => function ($e) {
                                return $e->getMeals()->getName();
                            },
                        ])
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
            $repository = db::em()->getRepository(MenuDays::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Menu Day not found",
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
