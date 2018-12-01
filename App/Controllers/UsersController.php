<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\UsersModel;

class UsersController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function find(Request $request, Response $response, $args): Response
    {
        return UsersModel::find($args['id'], $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function findAll(Request $request, Response $response): Response
    {
        return UsersModel::findAll($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function create(Request $request, Response $response): Response
    {
        return UsersModel::create($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function update(Request $request, Response $response, $args): Response
    {
        return UsersModel::update($args['id'], $request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function remove(Request $request, Response $response, $args): Response
    {
        return UsersModel::remove($args['id'], $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function autocompleteOm(Request $request, Response $response, $args): Response
    {
        return UsersModel::autocompleteOm($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function allOmsFromUser(Request $request, Response $response, $args): Response
    {
        return UsersModel::allOmsFromUser($args['id'], $request, $response);
    }
}
