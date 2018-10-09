<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\SuppliersController;
use App\Middlewares\AuthenticationMiddleware;

class SuppliersRoute
{

    public static function setUp(App $app)
    {
        $app->group('', function() {
                $this->get('/api/v1/suppliers', SuppliersController::class . ":findAll");

                $this->get('/api/v1/suppliers/{id:[0-9]+}', SuppliersController::class . ":find");

                $this->post('/api/v1/suppliers', SuppliersController::class . ":create");

                $this->options('/api/v1/suppliers', function() {
                    header("Access-Control-Allow-Methods: POST, OPTIONS");
                });

                $this->put('/api/v1/suppliers/{id:[0-9]+}', SuppliersController::class . ":update");

                $this->delete('/api/v1/suppliers/{id:[0-9]+}', SuppliersController::class . ":remove");

                $this->options('/api/v1/suppliers/{id:[0-9]+}', function() {
                    header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
                });
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
