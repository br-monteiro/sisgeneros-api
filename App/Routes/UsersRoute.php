<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\UsersController;
use App\Middlewares\AuthenticationMiddleware;

class UsersRoute
{

    public static function setUp(App $app)
    {
        $app->group('', function() {
                $this->get('/api/v1/users', UsersController::class . ":findAll");

                $this->get('/api/v1/users/{id:[0-9]+}', UsersController::class . ":find");

                $this->post('/api/v1/users', UsersController::class . ":create");

                $this->options('/api/v1/users', function() {
                    header("Access-Control-Allow-Methods: POST, OPTIONS");
                });

                $this->put('/api/v1/users/{id:[0-9]+}', UsersController::class . ":update");

                $this->delete('/api/v1/users/{id:[0-9]+}', UsersController::class . ":remove");

                $this->options('/api/v1/users/{id:[0-9]+}', function() {
                    header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
                });
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
