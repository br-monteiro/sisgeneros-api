<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\RecipesController;
use App\Middlewares\AuthenticationMiddleware;

class RecipesRoute
{

    public static function setUp(App $app)
    {
        $app->group('', function() {
                $this->get('/api/v1/recipes', RecipesController::class . ":findAll");

                $this->get('/api/v1/recipes/{id:[0-9]+}', RecipesController::class . ":find");

                $this->post('/api/v1/recipes', RecipesController::class . ":create");

                $this->options('/api/v1/recipes', function() {
                    header("Access-Control-Allow-Methods: POST, OPTIONS");
                });

                $this->put('/api/v1/recipes/{id:[0-9]+}', RecipesController::class . ":update");

                $this->delete('/api/v1/recipes/{id:[0-9]+}', RecipesController::class . ":remove");

                $this->options('/api/v1/recipes/{id:[0-9]+}', function() {
                    header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
                });
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
