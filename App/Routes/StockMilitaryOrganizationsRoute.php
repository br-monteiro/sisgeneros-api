<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\StockMilitaryOrganizationsController;

class StockMilitaryOrganizationsRoute
{

    public static function setUp(App $app)
    {
        $app->get('/api/v1/stockmilitaryorganizations', StockMilitaryOrganizationsController::class . ":findAll");

        $app->get('/api/v1/stockmilitaryorganizations/{id:[0-9]+}', StockMilitaryOrganizationsController::class . ":find");

        $app->post('/api/v1/stockmilitaryorganizations', StockMilitaryOrganizationsController::class . ":create");

        $app->options('/api/v1/stockmilitaryorganizations', function($request, $response) {
            header("Access-Control-Allow-Methods: POST, OPTIONS");
        });

        $app->put('/api/v1/stockmilitaryorganizations/{id:[0-9]+}', StockMilitaryOrganizationsController::class . ":update");

        $app->delete('/api/v1/stockmilitaryorganizations/{id:[0-9]+}', StockMilitaryOrganizationsController::class . ":remove");

        $app->options('/api/v1/stockmilitaryorganizations/{id:[0-9]+}', function($request, $response) {
            header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
        });
    }
}
