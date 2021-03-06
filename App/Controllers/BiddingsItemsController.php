<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\BiddingsItemsModel;
use App\Helpers\AccessHelper as access;
use App\Exceptions\AccessDeniedException;

class BiddingsItemsController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function find(Request $request, Response $response, $args): Response
    {
        try {
            access::check($request, ['root']);
            return BiddingsItemsModel::find($args['id'], $response);
        } catch (AccessDeniedException $ex) {
            return access::message($response, $ex);
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function findAll(Request $request, Response $response, $args): Response
    {
        return BiddingsItemsModel::findAll($args, $request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function create(Request $request, Response $response): Response
    {
        return BiddingsItemsModel::create($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function update(Request $request, Response $response, $args): Response
    {
        return BiddingsItemsModel::update($args['id'], $request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function remove(Request $request, Response $response, $args): Response
    {
        return BiddingsItemsModel::remove($args['id'], $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function search(Request $request, Response $response, $args): Response
    {
        return BiddingsItemsModel::search($args, $request, $response);
    }
}
