<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\BiddingsController;

class BiddingsRoute
{

    public static function setUp(App $app)
    {
        $app->get('/api/v1/biddings', BiddingsController::class . ":findAll");

        $app->get('/api/v1/biddings/{id:[0-9]+}', BiddingsController::class . ":find");

        $app->post('/api/v1/biddings', BiddingsController::class . ":create");

        $app->options('/api/v1/biddings', function($request, $response) {
            header("Access-Control-Allow-Methods: POST, OPTIONS");
        });

        $app->put('/api/v1/biddings/{id:[0-9]+}', BiddingsController::class . ":update");

        $app->delete('/api/v1/biddings/{id:[0-9]+}', BiddingsController::class . ":remove");

        $app->options('/api/v1/biddings/{id:[0-9]+}', function($request, $response) {
            header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
        });
    }
}
