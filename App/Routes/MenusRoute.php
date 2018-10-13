<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\MenusController;
use App\Middlewares\AuthenticationMiddleware;

class MenusRoute
{

    public static function setUp(App $app)
    {
        $app->group('', function() {
                $this->get('/api/v1/menus', MenusController::class . ":findAll");

                $this->get('/api/v1/menus/{id:[0-9]+}', MenusController::class . ":find");

                $this->post('/api/v1/menus', MenusController::class . ":create");

                $this->options('/api/v1/menus', function() {
                    header("Access-Control-Allow-Methods: POST, OPTIONS");
                });

                $this->put('/api/v1/menus/{id:[0-9]+}', MenusController::class . ":update");

                $this->delete('/api/v1/menus/{id:[0-9]+}', MenusController::class . ":remove");

                $this->options('/api/v1/menus/{id:[0-9]+}', function() {
                    header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
                });
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
