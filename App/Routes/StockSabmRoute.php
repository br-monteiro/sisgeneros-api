<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\StockSabmController;
use App\Middlewares\AuthenticationMiddleware;

class StockSabmRoute
{

    public static function setUp(App $app)
    {
        $app->options('/api/v1/stocksabm', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/api/v1/stocksabm/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/api/v1/stocksabm', StockSabmController::class . ":findAll");

                $this->get('/api/v1/stocksabm/{id:[0-9]+}', StockSabmController::class . ":find");

                $this->post('/api/v1/stocksabm', StockSabmController::class . ":create");

                $this->put('/api/v1/stocksabm/{id:[0-9]+}', StockSabmController::class . ":update");

                $this->delete('/api/v1/stocksabm/{id:[0-9]+}', StockSabmController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
