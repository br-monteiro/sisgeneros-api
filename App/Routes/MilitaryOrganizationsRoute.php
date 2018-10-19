<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\MilitaryOrganizationsController;
use App\Middlewares\AuthenticationMiddleware;

class MilitaryOrganizationsRoute
{

    public static function setUp(App $app)
    {
        $app->options('/api/v1/militaryorganizations', function() {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        });

        $app->options('/api/v1/militaryorganizations/{id:[0-9]+}', function() {
            header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
        });

        $app->group('', function() {
                $this->get('/api/v1/militaryorganizations', MilitaryOrganizationsController::class . ":findAll");

                $this->get('/api/v1/militaryorganizations/{id:[0-9]+}', MilitaryOrganizationsController::class . ":find");

                $this->post('/api/v1/militaryorganizations', MilitaryOrganizationsController::class . ":create");

                $this->put('/api/v1/militaryorganizations/{id:[0-9]+}', MilitaryOrganizationsController::class . ":update");

                $this->delete('/api/v1/militaryorganizations/{id:[0-9]+}', MilitaryOrganizationsController::class . ":remove");
            })
            ->add(AuthenticationMiddleware::class . ':verify');
    }
}
