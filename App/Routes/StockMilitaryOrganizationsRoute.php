<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\StockMilitaryOrganizationsController;
use App\Middlewares\AuthenticationMiddleware;

class StockMilitaryOrganizationsRoute
{

    public static function setUp(App $app)
    {
        $app->options('/v1/stockmilitaryorganizations', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/v1/stockmilitaryorganizations/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/v1/stockmilitaryorganizations', StockMilitaryOrganizationsController::class . ":findAll");

                $this->get('/v1/stockmilitaryorganizations/{id:[0-9]+}', StockMilitaryOrganizationsController::class . ":find");

                $this->post('/v1/stockmilitaryorganizations', StockMilitaryOrganizationsController::class . ":create");

                $this->put('/v1/stockmilitaryorganizations/{id:[0-9]+}', StockMilitaryOrganizationsController::class . ":update");

                $this->delete('/v1/stockmilitaryorganizations/{id:[0-9]+}', StockMilitaryOrganizationsController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
