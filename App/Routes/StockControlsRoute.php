<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\StockControlsController;
use App\Middlewares\AuthenticationMiddleware;

class StockControlsRoute
{

    public static function setUp(App $app)
    {
        $app->options('/v1/stockcontrols', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/v1/stockcontrols/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/v1/stockcontrols', StockControlsController::class . ":findAll");

                $this->get('/v1/stockcontrols/{id:[0-9]+}', StockControlsController::class . ":find");

                $this->post('/v1/stockcontrols', StockControlsController::class . ":create");

                $this->put('/v1/stockcontrols/{id:[0-9]+}', StockControlsController::class . ":update");

                $this->delete('/v1/stockcontrols/{id:[0-9]+}', StockControlsController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
