<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\MenuDaysController;
use App\Middlewares\AuthenticationMiddleware;

class MenuDaysRoute
{

    public static function setUp(App $app)
    {
        $app->options('/api/v1/menudays', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/api/v1/menudays/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/api/v1/menudays', MenuDaysController::class . ":findAll");

                $this->get('/api/v1/menudays/{id:[0-9]+}', MenuDaysController::class . ":find");

                $this->post('/api/v1/menudays', MenuDaysController::class . ":create");

                $this->put('/api/v1/menudays/{id:[0-9]+}', MenuDaysController::class . ":update");

                $this->delete('/api/v1/menudays/{id:[0-9]+}', MenuDaysController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
