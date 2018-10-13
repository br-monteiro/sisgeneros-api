<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\StockMilitaryOrganizationsController;
use App\Middlewares\AuthenticationMiddleware;

class StockMilitaryOrganizationsRoute
{

    public static function setUp(App $app)
    {
        $app->group('', function() {
                $this->get('/api/v1/stockmilitaryorganizations', StockMilitaryOrganizationsController::class . ":findAll");

                $this->get('/api/v1/stockmilitaryorganizations/{id:[0-9]+}', StockMilitaryOrganizationsController::class . ":find");

                $this->post('/api/v1/stockmilitaryorganizations', StockMilitaryOrganizationsController::class . ":create");

                $this->options('/api/v1/stockmilitaryorganizations', function() {
                    header("Access-Control-Allow-Methods: POST, OPTIONS");
                });

                $this->put('/api/v1/stockmilitaryorganizations/{id:[0-9]+}', StockMilitaryOrganizationsController::class . ":update");

                $this->delete('/api/v1/stockmilitaryorganizations/{id:[0-9]+}', StockMilitaryOrganizationsController::class . ":remove");

                $this->options('/api/v1/stockmilitaryorganizations/{id:[0-9]+}', function() {
                    header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
                });
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
