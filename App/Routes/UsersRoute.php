<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\UsersController;

class UsersRoute
{

    public static function setUp(App $app)
    {
        $app->get('/api/v1/users', UsersController::class . ":findAll");

        $app->get('/api/v1/users/{id:[0-9]+}', UsersController::class . ":find");

        $app->post('/api/v1/users', UsersController::class . ":create");

        $app->options('/api/v1/users', function($request, $response) {
            header("Access-Control-Allow-Methods: POST, OPTIONS");
        });

        $app->put('/api/v1/users/{id:[0-9]+}', UsersController::class . ":update");

        $app->delete('/api/v1/users/{id:[0-9]+}', UsersController::class . ":remove");

        $app->options('/api/v1/users/{id:[0-9]+}', function($request, $response) {
            header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
        });
    }
}
