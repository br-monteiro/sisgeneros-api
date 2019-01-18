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
use App\Exceptions\InvalidIdentificationsException;
use App\Entities\RecipesPatterns;
use App\Entities\RecipesPatternsItems;
use App\Entities\MilitaryOrganizations;
use App\Models\StockMilitaryOrganizationsModel;
use App\Models\StockSabmModel;
use App\Models\BiddingsItemsModel;

class RecipesPatternsModel extends AbstractModel
{

    /**
     * Returns all register
     * @param Response $response
     * @return Response
     */
    public static function findAll(Request $request, Response $response): Response
    {
        try {

            $query = $request->getParam('query');

            $paginator = paginator::buildAttributes($request, 'recipes_patterns');
            $limit = $paginator->limit;
            $offset = $paginator->offset;
            $repository = db::em()->getRepository(RecipesPatterns::class);

            if ($query) {
                $entity = $repository->createQueryBuilder('rp')
                    ->where('rp.name LIKE :term')
                    ->setParameter('term', '%' . $query . '%')
                    ->setFirstResult($offset)
                    ->setMaxResults($limit)
                    ->orderBy('rp.name')
                    ->getQuery()
                    ->getResult();
            } else {
                $entity = $repository->findBy([], null, $limit, $offset);
            }

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
     * Returns all register
     * @param Response $response
     * @return Response
     */
    public static function findAllRecipesItemsByRecipesId(Request $request, Response $response): Response
    {
        try {
            $recipesId = $request->getParam('ids') ?? $request->getAttribute('id');

            if (!$recipesId) {
                throw new InvalidIdentificationsException("Invalid identifications");
            }


            $repository = db::em()->getRepository(RecipesPatternsItems::class);
            $entity = $repository->createQueryBuilder('rp')
                ->where('rp.recipesPatterns IN (' . self::buildIds($recipesId) . ')')
                ->orderBy('rp.name')
                ->getQuery()
                ->getResult();

            $values = self::outputValidate($entity)
                ->withoutAttribute('id')
                ->withAttribute('recipesPatterns', function ($e) {
                    return $e->getRecipesPatterns()->getId();
                }, true)
                ->run();

            $values = self::buildResult($values);

            $values = self::fetchStocks($request, $values);

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "data" => $values
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        } catch (InvalidIdentificationsException $ex) {
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
            $repository = db::em()->getRepository(RecipesPatterns::class);
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
                        ->withoutAttribute('militaryOrganizations')
                        ->withAttribute('items', function ($e) {
                                return self::getAllRecipesItems($e);
                            })
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        }
    }

    /**
     * Return one register by ID
     * @param int $id
     * @param Response $response
     * @return Response
     */
    public static function findRecipeItemsByRecipesId(int $id, Response $response): Response
    {
        try {
            $repository = db::em()->getRepository(RecipesPatternsItems::class);
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
                        ->withoutAttribute('recipesPatterns')
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

        if (!self::inputValidate($data, 'recipes_patterns_schema.json')) {
            return $response->withJson([
                    "message" => "There are wrong fields in submission",
                    "status" => "error",
                    "error" => Json::getValidateErrors()
                    ], 400);
        }

        db::em()->getConnection()->beginTransaction();

        try {

            $militaryOrganizations = db::em()
                ->getRepository(MilitaryOrganizations::class)
                ->find($data->militaryOrganizationsId);

            $entity = new RecipesPatterns();
            $entity->setName($data->name);
            $entity->setMilitaryOrganizations($militaryOrganizations);

            db::em()->persist($entity);
            // flush transaction
            db::em()->flush();

            // register items of Recipe
            if (isset($data->items)) {
                foreach ($data->items as $item) {
                    $obj = new RecipesPatternsItems();
                    $obj->setName($item->name);
                    $obj->setQuantity($item->quantity);
                    $obj->setRecipesPatterns($entity);
                    db::em()->persist($obj);
                    // flush transaction
                    db::em()->flush();
                }
            }

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
            $repository = db::em()->getRepository(RecipesPatterns::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Recipe not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'recipes_patterns_schema.json')) {
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
                        ->withoutAttribute('militaryOrganizations')
                        ->withAttribute('items', function ($e) {
                                return self::getAllRecipesItems($e);
                            })
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
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
    public static function updateRecipesItems(int $id, Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody()->getContents()) ?? [];

        try {
            $repository = db::em()->getRepository(RecipesPatternsItems::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Recipe not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'recipes_patterns_items_schema.json')) {
                return $response->withJson([
                        "message" => "There are wrong fields in submission",
                        "status" => "error",
                        "error" => Json::getValidateErrors()
                        ], 400);
            }

            $entity->setName($data->name);
            $entity->setQuantity($data->quantity);

            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry updated successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withoutAttribute('recipesPatterns')
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
            $repository = db::em()->getRepository(RecipesPatterns::class);
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

    /**
     * Remove one register by ID
     * @param int $id
     * @param Response $response
     * @return Response
     */
    public static function removeRecipesItems(int $id, Response $response): Response
    {
        try {
            $repository = db::em()->getRepository(RecipesPatternsItems::class);
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
        $query = "SELECT id, name, quantity FROM recipes_patterns_items AS rpi WHERE rpi.recipes_patterns_id = ?";
        $stmt = db::em()->getConnection()->prepare($query);
        $stmt->execute([$entity->getId()]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    private static function buildIds($ids): string
    {
        $result = [];
        if ($ids) {
            $explodedIds = explode(",", $ids);
            foreach ($explodedIds as $id) {
                $processedId = intval($id);
                if ($processedId > 0) {
                    $result[] = $processedId;
                }
            }
        }
        return implode(",", $result);
    }

    private static function buildResult(array $values): array
    {
        $result = [];
        foreach ($values as $value) {
            if (isset($result[$value->name])) {
                if (!isset($result[$value->name]->recipesIds[$value->recipesPatterns])) {
                    $result[$value->name]->recipesIds[] = $value->recipesPatterns;
                }
                $result[$value->name]->quantity += $value->quantity;
            } else {
                $value->recipesIds = [$value->recipesPatterns];
                unset($value->recipesPatterns);
                $result[$value->name] = $value;
            }
        }
        return array_values($result);
    }

    private static function fetchStocks(Request $request, array $values)
    {
        $omId = $request->getParam('omId', 1);
        $extrasAttributes = [
            "available" => false,
            "informed" => ""
        ];
        foreach ($values as $value) {
            $entityStockOm = StockMilitaryOrganizationsModel::rawEntitiesResultByMilitaryOrganizationsId($omId, $value->name);
            $value->stockOm = self::outputValidate($entityStockOm)
                ->withoutAttribute('militaryOrganizations')
                ->withAttribute($extrasAttributes, null, true)
                ->run()[0];

            $entityStockOmCeim = StockMilitaryOrganizationsModel::rawEntitiesResultByMilitaryOrganizationsId(null, $value->name, 'yes');
            $value->stockCeim = self::outputValidate($entityStockOmCeim)
                ->withoutAttribute('militaryOrganizations')
                ->withAttribute($extrasAttributes, null, true)
                ->run()[0];

            $entityStockSabm = StockSabmModel::rawEntitiesByItemName($value->name);
            $value->stockSabm = self::outputValidate($entityStockSabm)
                ->withoutAttribute('militaryOrganizations')
                ->withAttribute($extrasAttributes, null, true)
                ->run()[0];

            $entityBiddings = BiddingsItemsModel::rawEntitiesByItemName($value->name);
            $biddingsItems = self::outputValidate($entityBiddings)
                ->withoutAttribute(['biddings', 'suppliers'])
                ->run();
            $value->biddingsItems = array_reduce($biddingsItems, function($acc, $item) {
                if (!isset($acc->quantity)) {
                    $acc->quantity = 0;
                }
                if (isset($item->currentQuantity) && $item->currentQuantity) {
                    $acc->quantity += $item->currentQuantity;
                }
                return $acc;
            }, (object) $extrasAttributes);

            $value->notBiddingsItems = (object) $extrasAttributes;
        }
        return $values;
    }
}
