<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\UsersController;
use App\Middlewares\AuthenticationMiddleware;

class UsersRoute
{

    public static function setUp(App $app)
    {
        $app->options('/api/v1/users', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/api/v1/users/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->options('/api/v1/users/autocomplete/oms', function() {
            header("Access-Control-Allow-Methods: GET, OPTIONS");
        });

        $app->options('/api/v1/users/{id:[0-9]+}/oms', function() {
            header("Access-Control-Allow-Methods: GET, PUT, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/api/v1/users', UsersController::class . ":findAll");

                $this->get('/api/v1/users/{id:[0-9]+}', UsersController::class . ":find");

                $this->get('/api/v1/users/autocomplete/oms', UsersController::class . ":autocompleteOm");

                $this->get('/api/v1/users/{id:[0-9]+}/oms', UsersController::class . ":allOmsFromUser");

                $this->put('/api/v1/users/{id:[0-9]+}/oms', UsersController::class . ":saveProfile");

                $this->post('/api/v1/users', UsersController::class . ":create");

                $this->put('/api/v1/users/{id:[0-9]+}', UsersController::class . ":update");

                $this->delete('/api/v1/users/{id:[0-9]+}', UsersController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
