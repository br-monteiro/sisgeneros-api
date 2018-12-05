<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\BiddingsItemsController;
use App\Middlewares\AuthenticationMiddleware;

class BiddingsItemsRoute
{

    public static function setUp(App $app)
    {
        $app->options('/api/v1/biddingsitems', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/api/v1/biddingsitems/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

         $app->options('/api/v1/biddingsitems/bidding/{biddingId:[0-9]+}', function() {
             header("Access-Control-Allow-Methods: GET, OPTIONS");
         });

         $app->options('/api/v1/biddingsitems/bidding/{biddingId:[0-9]+}/search', function() {
             header("Access-Control-Allow-Methods: GET, OPTIONS");
         });

        $app->group('', function() {
                $this->get('/api/v1/biddingsitems', BiddingsItemsController::class . ":findAll");

                $this->get('/api/v1/biddingsitems/{id:[0-9]+}', BiddingsItemsController::class . ":find");

                 $this->get('/api/v1/biddingsitems/bidding/{biddingId:[0-9]+}', BiddingsItemsController::class . ":findAll");

                 $this->get('/api/v1/biddingsitems/bidding/{biddingId:[0-9]+}/search', BiddingsItemsController::class . ":search");

                $this->post('/api/v1/biddingsitems', BiddingsItemsController::class . ":create");

                $this->put('/api/v1/biddingsitems/{id:[0-9]+}', BiddingsItemsController::class . ":update");

                $this->delete('/api/v1/biddingsitems/{id:[0-9]+}', BiddingsItemsController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
