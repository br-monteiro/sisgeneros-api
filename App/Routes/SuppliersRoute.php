<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\SuppliersController;

class SuppliersRoute
{

    public static function setUp(App $app)
    {
        $app->get('/api/v1/suppliers', SuppliersController::class . ":findAll");

        $app->get('/api/v1/suppliers/{id:[0-9]+}', SuppliersController::class . ":find");

        $app->post('/api/v1/suppliers', SuppliersController::class . ":create");

        $app->options('/api/v1/suppliers', function($request, $response) {
            header("Access-Control-Allow-Methods: POST, OPTIONS");
        });

        $app->put('/api/v1/suppliers/{id:[0-9]+}', SuppliersController::class . ":update");

        $app->delete('/api/v1/suppliers/{id:[0-9]+}', SuppliersController::class . ":remove");

        $app->options('/api/v1/suppliers/{id:[0-9]+}', function($request, $response) {
            header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
        });
    }
}
