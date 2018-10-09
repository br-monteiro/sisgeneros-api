<?php
namespace App\Middlewares;

use Slim\Http\Response;
use Slim\Http\Request;
use App\Helpers\AuthenticationHelper;

class AuthenticationMiddleware
{

    /**
     * Verify the authenticate os user
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Slim\Http\Response
     */
    public static function verify(Request $request, Response $response, $next)
    {
        try {
            if (AuthenticationHelper::isValidLogin($request)) {
                return $next($request, $response);
            }
        } catch (\Exception $ex) {
            return $response->withJson([
                    "message" => $ex->getMessage(),
                    "status" => "error"
                    ], 401);
        }
    }
}
