<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\StockControlsController;
use App\Middlewares\AuthenticationMiddleware;

class StockControlsRoute
{

    public static function setUp(App $app)
    {
        $app->group('', function() {
                $this->get('/api/v1/stockcontrols', StockControlsController::class . ":findAll");

                $this->get('/api/v1/stockcontrols/{id:[0-9]+}', StockControlsController::class . ":find");

                $this->post('/api/v1/stockcontrols', StockControlsController::class . ":create");

                $this->options('/api/v1/stockcontrols', function() {
                    header("Access-Control-Allow-Methods: POST, OPTIONS");
                });

                $this->put('/api/v1/stockcontrols/{id:[0-9]+}', StockControlsController::class . ":update");

                $this->delete('/api/v1/stockcontrols/{id:[0-9]+}', StockControlsController::class . ":remove");

                $this->options('/api/v1/stockcontrols/{id:[0-9]+}', function() {
                    header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
                });
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
