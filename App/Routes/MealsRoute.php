<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\MealsController;
use App\Middlewares\AuthenticationMiddleware;

class MealsRoute
{

    public static function setUp(App $app)
    {
        $app->group('', function() {
                $this->get('/api/v1/meals', MealsController::class . ":findAll");

                $this->get('/api/v1/meals/{id:[0-9]+}', MealsController::class . ":find");

                $this->post('/api/v1/meals', MealsController::class . ":create");

                $this->options('/api/v1/meals', function() {
                    header("Access-Control-Allow-Methods: POST, OPTIONS");
                });

                $this->put('/api/v1/meals/{id:[0-9]+}', MealsController::class . ":update");

                $this->delete('/api/v1/meals/{id:[0-9]+}', MealsController::class . ":remove");

                $this->options('/api/v1/meals/{id:[0-9]+}', function() {
                    header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
                });
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
