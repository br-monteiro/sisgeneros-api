<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\RecipesPatternsController;
use App\Middlewares\AuthenticationMiddleware;

class RecipesPatternsRoute
{

    public static function setUp(App $app)
    {
        $app->options('/v1/recipespatterns', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/v1/recipespatterns/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->options('/v1/recipespatterns/items', function() {
            header("Access-Control-Allow-Methods: GET, OPTIONS");
        });

        $app->options('/v1/recipespatterns/items/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, OPTIONS");
        });

        $app->options('/v1/recipespatterns/recipe/item/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/v1/recipespatterns', RecipesPatternsController::class . ":findAll");

                $this->get('/v1/recipespatterns/{id:[0-9]+}', RecipesPatternsController::class . ":find");

                $this->get('/v1/recipespatterns/items', RecipesPatternsController::class . ":findAllRecipesItemsByRecipesId");

                $this->get('/v1/recipespatterns/items/{id:[0-9]+}', RecipesPatternsController::class . ":findAllRecipesItemsByRecipesId");

                $this->get('/v1/recipespatterns/recipe/item/{id:[0-9]+}', RecipesPatternsController::class . ":findRecipeItemsByRecipesId");

                $this->put('/v1/recipespatterns/recipe/item/{id:[0-9]+}', RecipesPatternsController::class . ":updateRecipesItems");

                $this->delete('/v1/recipespatterns/recipe/item/{id:[0-9]+}', RecipesPatternsController::class . ":removeRecipesItems");

                $this->post('/v1/recipespatterns', RecipesPatternsController::class . ":create");

                $this->put('/v1/recipespatterns/{id:[0-9]+}', RecipesPatternsController::class . ":update");

                $this->delete('/v1/recipespatterns/{id:[0-9]+}', RecipesPatternsController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
