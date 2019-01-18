<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\RecipesPatternsModel;

class RecipesPatternsController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function find(Request $request, Response $response, $args): Response
    {
        return RecipesPatternsModel::find($args['id'], $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function findAll(Request $request, Response $response): Response
    {
        return RecipesPatternsModel::findAll($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function findAllRecipesItemsByRecipesId(Request $request, Response $response): Response
    {
        return RecipesPatternsModel::findAllRecipesItemsByRecipesId($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function findRecipeItemsByRecipesId(Request $request, Response $response, $args): Response
    {
        return RecipesPatternsModel::findRecipeItemsByRecipesId($args['id'], $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function create(Request $request, Response $response): Response
    {
        return RecipesPatternsModel::create($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function update(Request $request, Response $response, $args): Response
    {
        return RecipesPatternsModel::update($args['id'], $request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function updateRecipesItems(Request $request, Response $response, $args): Response
    {
        return RecipesPatternsModel::updateRecipesItems($args['id'], $request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function remove(Request $request, Response $response, $args): Response
    {
        return RecipesPatternsModel::remove($args['id'], $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function removeRecipesItems(Request $request, Response $response, $args): Response
    {
        return RecipesPatternsModel::removeRecipesItems($args['id'], $response);
    }
}
