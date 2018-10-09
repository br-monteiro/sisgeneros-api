<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\AuthModel;

class AuthController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function login(Request $request, Response $response): Response
    {
        return AuthModel::login($request, $response);
    }
}
