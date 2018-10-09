<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\RecipesPatternsController;

class RecipesPatternsRoute
{

    public static function setUp(App $app)
    {
        $app->get('/api/v1/recipespatterns', RecipesPatternsController::class . ":findAll");

        $app->get('/api/v1/recipespatterns/{id:[0-9]+}', RecipesPatternsController::class . ":find");

        $app->post('/api/v1/recipespatterns', RecipesPatternsController::class . ":create");

        $app->options('/api/v1/recipespatterns', function($request, $response) {
            header("Access-Control-Allow-Methods: POST, OPTIONS");
        });

        $app->put('/api/v1/recipespatterns/{id:[0-9]+}', RecipesPatternsController::class . ":update");

        $app->delete('/api/v1/recipespatterns/{id:[0-9]+}', RecipesPatternsController::class . ":remove");

        $app->options('/api/v1/recipespatterns/{id:[0-9]+}', function($request, $response) {
            header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
        });
    }
}
