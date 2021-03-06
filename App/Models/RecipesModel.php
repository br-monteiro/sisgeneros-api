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
use App\Entities\Recipes;
use App\Entities\RecipesItems;
use App\Entities\MenuDays;

class RecipesModel extends AbstractModel
{

    /**
     * Returns all register
     * @param Response $response
     * @return Response
     */
    public static function findAll(Request $request, Response $response): Response
    {
        try {

            $paginator = paginator::buildAttributes($request, 'recipes');
            $limit = $paginator->limit;
            $offset = $paginator->offset;
            $repository = db::em()->getRepository(Recipes::class);
            $entity = $repository->findBy([], null, $limit, $offset);

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "allResults" => $paginator->allResults,
                    "limit" => $limit,
                    "offset" => $offset,
                    "page" => $paginator->page,
                    "data" => self::outputValidate($entity)
                        ->withAttribute('menuDays', function($e) {
                                return $e->getMenuDays()->getDate()->format('Y-m-d');
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
            $repository = db::em()->getRepository(Recipes::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Recipe not found",
                            "status" => "error"
                            ], 404);
            }

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withAttribute([
                            'menuDays' => function($e) {
                                return $e->getMenuDays()->getDate()->format('Y-m-d');
                            },
                            'items' => function($e) {
                                return self::getAllRecipesItems($e);
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
        $data = json_decode($request->getBody()->getContents()) ?? [];

        if (!self::inputValidate($data, 'recipes_schema.json')) {
            return $response->withJson([
                    "message" => "There are wrong fields in submission",
                    "status" => "error",
                    "error" => Json::getValidateErrors()
                    ], 400);
        }

        db::em()->getConnection()->beginTransaction();

        try {

            $menuDays = db::em()
                ->getRepository(MenuDays::class)
                ->find($data->menuDaysId);

            $entity = new Recipes();
            $entity->setName($data->name);
            $entity->setMenuDays($menuDays);

            db::em()->persist($entity);
            // flush transaction
            db::em()->flush();

            // register items of Recipe
            if (isset($data->items)) {
                $objItems = [];
                foreach ($data->items as $i => $item) {
                    $objItems[$i] = new RecipesItems();
                    $objItems[$i]->setName($item->name);
                    $objItems[$i]->setRecipes($entity);
                    db::em()->persist($objItems[$i]);
                    // flush transaction
                    db::em()->flush();
                }
            }

            // commit transaction
            db::em()->getConnection()->commit();

            return $response->withJson([
                    "message" => "Registry created successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)->run()
                    ], 201);
        } catch (ORMException $ex) {
            db::em()->getConnection()->rollBack();
            return self::commonError($response, $ex);
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
        $data = json_decode($request->getBody()->getContents()) ?? [];

        try {
            $repository = db::em()->getRepository(Recipes::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Recipe not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'recipes_schema.json')) {
                return $response->withJson([
                        "message" => "There are wrong fields in submission",
                        "status" => "error",
                        "error" => Json::getValidateErrors()
                        ], 400);
            }

            $entity->setName($data->name);

            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry updated successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withAttribute('menuDays', function($e) {
                                return $e->getMenuDays()->getDate()->format('Y-m-d');
                            })
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
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
            $repository = db::em()->getRepository(Recipes::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Recipe not found",
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

    public static function getAllRecipesItems($entity)
    {
        $query = "SELECT id, name FROM recipes_items AS ri WHERE ri.recipes_id = ?";
        $stmt = db::em()->getConnection()->prepare($query);
        $stmt->execute([$entity->getId()]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
