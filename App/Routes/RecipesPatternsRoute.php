<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\RecipesPatternsController;
use App\Middlewares\AuthenticationMiddleware;

class RecipesPatternsRoute
{

    public static function setUp(App $app)
    {
        $app->group('', function() {
                $this->get('/api/v1/recipespatterns', RecipesPatternsController::class . ":findAll");

                $this->get('/api/v1/recipespatterns/{id:[0-9]+}', RecipesPatternsController::class . ":find");

                $this->post('/api/v1/recipespatterns', RecipesPatternsController::class . ":create");

                $this->options('/api/v1/recipespatterns', function() {
                    header("Access-Control-Allow-Methods: POST, OPTIONS");
                });

                $this->put('/api/v1/recipespatterns/{id:[0-9]+}', RecipesPatternsController::class . ":update");

                $this->delete('/api/v1/recipespatterns/{id:[0-9]+}', RecipesPatternsController::class . ":remove");

                $this->options('/api/v1/recipespatterns/{id:[0-9]+}', function() {
                    header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
                });
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
