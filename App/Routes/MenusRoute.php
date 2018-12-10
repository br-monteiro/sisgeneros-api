<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\MenusController;
use App\Middlewares\AuthenticationMiddleware;

class MenusRoute
{

    public static function setUp(App $app)
    {
        $app->options('/v1/menus', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/v1/menus/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/v1/menus', MenusController::class . ":findAll");

                $this->get('/v1/menus/{id:[0-9]+}', MenusController::class . ":find");

                $this->post('/v1/menus', MenusController::class . ":create");

                $this->put('/v1/menus/{id:[0-9]+}', MenusController::class . ":update");

                $this->delete('/v1/menus/{id:[0-9]+}', MenusController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
