<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\RecipesController;
use App\Middlewares\AuthenticationMiddleware;

class RecipesRoute
{

    public static function setUp(App $app)
    {
        $app->options('/v1/recipes', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/v1/recipes/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/v1/recipes', RecipesController::class . ":findAll");

                $this->get('/v1/recipes/{id:[0-9]+}', RecipesController::class . ":find");

                $this->post('/v1/recipes', RecipesController::class . ":create");

                $this->put('/v1/recipes/{id:[0-9]+}', RecipesController::class . ":update");

                $this->delete('/v1/recipes/{id:[0-9]+}', RecipesController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
