<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\MenuDaysController;
use App\Middlewares\AuthenticationMiddleware;

class MenuDaysRoute
{

    public static function setUp(App $app)
    {
        $app->options('/v1/menudays', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/v1/menudays/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->options('/v1/menudays/menu/{menuId:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/v1/menudays', MenuDaysController::class . ":findAll");

                $this->get('/v1/menudays/menu/{menuId:[0-9]+}', MenuDaysController::class . ":findAll");

                $this->get('/v1/menudays/{id:[0-9]+}', MenuDaysController::class . ":find");

                $this->post('/v1/menudays', MenuDaysController::class . ":create");

                $this->put('/v1/menudays/{id:[0-9]+}', MenuDaysController::class . ":update");

                $this->delete('/v1/menudays/{id:[0-9]+}', MenuDaysController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
