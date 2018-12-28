<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\SuppliersController;
use App\Middlewares\AuthenticationMiddleware;

class SuppliersRoute
{

    public static function setUp(App $app)
    {
        $app->options('/v1/suppliers', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/v1/suppliers/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->options('/v1/suppliers/search', function() {
            header("Access-Control-Allow-Methods: GET, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/v1/suppliers', SuppliersController::class . ":findAll");

                $this->get('/v1/suppliers/search', SuppliersController::class . ":findAll");

                $this->get('/v1/suppliers/{id:[0-9]+}', SuppliersController::class . ":find");

                $this->post('/v1/suppliers', SuppliersController::class . ":create");

                $this->put('/v1/suppliers/{id:[0-9]+}', SuppliersController::class . ":update");

                $this->delete('/v1/suppliers/{id:[0-9]+}', SuppliersController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
