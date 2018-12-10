<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\UsersController;
use App\Middlewares\AuthenticationMiddleware;

class UsersRoute
{

    public static function setUp(App $app)
    {
        $app->options('/v1/users', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/v1/users/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->options('/v1/users/autocomplete', function() {
            header("Access-Control-Allow-Methods: GET, OPTIONS");
        });

        $app->options('/v1/users/autocomplete/oms', function() {
            header("Access-Control-Allow-Methods: GET, OPTIONS");
        });

        $app->options('/v1/users/{id:[0-9]+}/oms', function() {
            header("Access-Control-Allow-Methods: GET, PUT, OPTIONS");
        });

        $app->options('/v1/users/{userId:[0-9]+}/{omId:[0-9]+}/oms', function() {
            header("Access-Control-Allow-Methods: DELETE, OPTIONS");
        });

        $app->options('/v1/users/{userId:[0-9]+}/{omId:[0-9]+}/oms/changedefault', function() {
            header("Access-Control-Allow-Methods: PUT, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/v1/users', UsersController::class . ":findAll");

                $this->get('/v1/users/{id:[0-9]+}', UsersController::class . ":find");

                $this->get('/v1/users/autocomplete', UsersController::class . ":autocomplete");

                $this->get('/v1/users/autocomplete/oms', UsersController::class . ":autocompleteOm");

                $this->get('/v1/users/{id:[0-9]+}/oms', UsersController::class . ":allOmsFromUser");

                $this->put('/v1/users/{id:[0-9]+}/oms', UsersController::class . ":saveProfile");

                $this->put('/v1/users/{id:[0-9]+}', UsersController::class . ":update");

                $this->put('/v1/users/{userId:[0-9]+}/{omId:[0-9]+}/oms/changedefault', UsersController::class . ":changeDefault");

                $this->post('/v1/users', UsersController::class . ":create");

                $this->delete('/v1/users/{id:[0-9]+}', UsersController::class . ":remove");

                $this->delete('/v1/users/{userId:[0-9]+}/{omId:[0-9]+}/oms', UsersController::class . ":removeProfile");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
