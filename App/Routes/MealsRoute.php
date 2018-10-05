<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\MealsController;

class MealsRoute
{

    public static function setUp(App $app)
    {
        $app->get('/api/v1/meals', MealsController::class . ":findAll");

        $app->get('/api/v1/meals/{id:[0-9]+}', MealsController::class . ":find");

        $app->post('/api/v1/meals', MealsController::class . ":create");

        $app->options('/api/v1/meals', function($request, $response) {
            header("Access-Control-Allow-Methods: POST, OPTIONS");
        });

        $app->put('/api/v1/meals/{id:[0-9]+}', MealsController::class . ":update");

        $app->delete('/api/v1/meals/{id:[0-9]+}', MealsController::class . ":remove");

        $app->options('/api/v1/meals/{id:[0-9]+}', function($request, $response) {
            header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
        });
    }
}
