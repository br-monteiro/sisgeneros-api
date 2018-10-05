<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\MealsModel;

class MealsController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function find(Request $request, Response $response, $args): Response
    {
        return MealsModel::find($args['id'], $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function findAll(Request $request, Response $response): Response
    {
        return MealsModel::findAll($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function create(Request $request, Response $response): Response
    {
        return MealsModel::create($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function update(Request $request, Response $response, $args): Response
    {
        return MealsModel::update($args['id'], $request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function remove(Request $request, Response $response, $args): Response
    {
        return MealsModel::remove($args['id'], $response);
    }
}
