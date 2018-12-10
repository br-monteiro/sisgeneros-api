<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\MealsController;
use App\Middlewares\AuthenticationMiddleware;

class MealsRoute
{

    public static function setUp(App $app)
    {
        $app->options('/v1/meals', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/v1/meals/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/v1/meals', MealsController::class . ":findAll");

                $this->get('/v1/meals/{id:[0-9]+}', MealsController::class . ":find");

                $this->post('/v1/meals', MealsController::class . ":create");

                $this->put('/v1/meals/{id:[0-9]+}', MealsController::class . ":update");

                $this->delete('/v1/meals/{id:[0-9]+}', MealsController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
