<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\BiddingsController;
use App\Middlewares\AuthenticationMiddleware;

class BiddingsRoute
{

    public static function setUp(App $app)
    {
        $app->options('/api/v1/biddings', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/api/v1/biddings/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/api/v1/biddings', BiddingsController::class . ":findAll");

                $this->get('/api/v1/biddings/{id:[0-9]+}', BiddingsController::class . ":find");

                $this->post('/api/v1/biddings', BiddingsController::class . ":create");

                $this->put('/api/v1/biddings/{id:[0-9]+}', BiddingsController::class . ":update");

                $this->delete('/api/v1/biddings/{id:[0-9]+}', BiddingsController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
