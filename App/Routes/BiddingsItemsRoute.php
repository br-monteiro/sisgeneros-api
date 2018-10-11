<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\BiddingsItemsController;
use App\Middlewares\AuthenticationMiddleware;

class BiddingsItemsRoute
{

    public static function setUp(App $app)
    {
        $app->group('', function() {
                $this->get('/api/v1/biddingsitems', BiddingsItemsController::class . ":findAll");

                $this->get('/api/v1/biddingsitems/{id:[0-9]+}', BiddingsItemsController::class . ":find");

                $this->post('/api/v1/biddingsitems', BiddingsItemsController::class . ":create");

                $this->options('/api/v1/biddingsitems', function() {
                    header("Access-Control-Allow-Methods: POST, OPTIONS");
                });

                $this->put('/api/v1/biddingsitems/{id:[0-9]+}', BiddingsItemsController::class . ":update");

                $this->delete('/api/v1/biddingsitems/{id:[0-9]+}', BiddingsItemsController::class . ":remove");

                $this->options('/api/v1/biddingsitems/{id:[0-9]+}', function() {
                    header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
                });
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
