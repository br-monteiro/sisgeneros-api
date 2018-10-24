<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\RecipesPatternsController;
use App\Middlewares\AuthenticationMiddleware;

class RecipesPatternsRoute
{

    public static function setUp(App $app)
    {
        $app->options('/api/v1/recipespatterns', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/api/v1/recipespatterns/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->options('/api/v1/recipespatterns/recipe/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/api/v1/recipespatterns', RecipesPatternsController::class . ":findAll");

                $this->get('/api/v1/recipespatterns/{id:[0-9]+}', RecipesPatternsController::class . ":find");

                $this->get('/api/v1/recipespatterns/recipe/{id:[0-9]+}', RecipesPatternsController::class . ":findAllRecipesItemsByRecipesId");

                $this->post('/api/v1/recipespatterns', RecipesPatternsController::class . ":create");

                $this->put('/api/v1/recipespatterns/{id:[0-9]+}', RecipesPatternsController::class . ":update");

                $this->delete('/api/v1/recipespatterns/{id:[0-9]+}', RecipesPatternsController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
