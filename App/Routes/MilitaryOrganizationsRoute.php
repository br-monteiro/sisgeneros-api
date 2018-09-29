<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\MilitaryOrganizationsController;

class MilitaryOrganizationsRoute
{

    public static function setUp(App $app)
    {
        $app->get('/api/v1/militaryorganizations', MilitaryOrganizationsController::class . ":findAll");

        $app->get('/api/v1/militaryorganizations/{id:[0-9]+}', MilitaryOrganizationsController::class . ":find");

        $app->post('/api/v1/militaryorganizations', MilitaryOrganizationsController::class . ":create");

        $app->options('/api/v1/militaryorganizations', function($request, $response) {
            header("Access-Control-Allow-Methods: POST, OPTIONS");
        });

        $app->put('/api/v1/militaryorganizations/{id:[0-9]+}', MilitaryOrganizationsController::class . ":update");

        $app->delete('/api/v1/militaryorganizations/{id:[0-9]+}', MilitaryOrganizationsController::class . ":remove");

        $app->options('/api/v1/militaryorganizations/{id:[0-9]+}', function($request, $response) {
            header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
        });
    }
}
