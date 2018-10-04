<?php
namespace App\Routes;

use Slim\App;

class Router
{

    public static function setUp(App $app)
    {
        // routes for test
        TestsRoute::setUp($app);
        // users routes
        UsersRoute::setUp($app);
        // military organizations
        MilitaryOrganizationsRoute::setUp($app);
        // suppliers
        SuppliersRoute::setUp($app);
        // stock military organizations
        StockMilitaryOrganizationsRoute::setUp($app);
    }
}
