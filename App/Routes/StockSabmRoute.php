<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\StockSabmController;

class StockSabmRoute
{

    public static function setUp(App $app)
    {
        $app->get('/api/v1/stocksabm', StockSabmController::class . ":findAll");

        $app->get('/api/v1/stocksabm/{id:[0-9]+}', StockSabmController::class . ":find");

        $app->post('/api/v1/stocksabm', StockSabmController::class . ":create");

        $app->options('/api/v1/stocksabm', function($request, $response) {
            header("Access-Control-Allow-Methods: POST, OPTIONS");
        });

        $app->put('/api/v1/stocksabm/{id:[0-9]+}', StockSabmController::class . ":update");

        $app->delete('/api/v1/stocksabm/{id:[0-9]+}', StockSabmController::class . ":remove");

        $app->options('/api/v1/stocksabm/{id:[0-9]+}', function($request, $response) {
            header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
        });
    }
}
