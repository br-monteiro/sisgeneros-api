<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\StockSabmModel;

class StockSabmController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function find(Request $request, Response $response, $args): Response
    {
        return StockSabmModel::find($args['id'], $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function findAll(Request $request, Response $response): Response
    {
        return StockSabmModel::findAll($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function create(Request $request, Response $response): Response
    {
        return StockSabmModel::create($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function update(Request $request, Response $response, $args): Response
    {
        return StockSabmModel::update($args['id'], $request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function remove(Request $request, Response $response, $args): Response
    {
        return StockSabmModel::remove($args['id'], $response);
    }
}
