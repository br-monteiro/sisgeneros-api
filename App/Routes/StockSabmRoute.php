<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\StockSabmController;
use App\Middlewares\AuthenticationMiddleware;

class StockSabmRoute
{

    public static function setUp(App $app)
    {
        $app->options('/v1/stocksabm', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/v1/stocksabm/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/v1/stocksabm', StockSabmController::class . ":findAll");

                $this->get('/v1/stocksabm/{id:[0-9]+}', StockSabmController::class . ":find");

                $this->post('/v1/stocksabm', StockSabmController::class . ":create");

                $this->put('/v1/stocksabm/{id:[0-9]+}', StockSabmController::class . ":update");

                $this->delete('/v1/stocksabm/{id:[0-9]+}', StockSabmController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
