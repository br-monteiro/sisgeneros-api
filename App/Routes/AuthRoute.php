<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\AuthController;

class AuthRoute
{

    public static function setUp(App $app)
    {
        $app->post('/v1/auth', AuthController::class . ":login");

        $app->options('/v1/auth', function() {
            header("Access-Control-Allow-Methods: POST, OPTIONS");
        });
    }
}
