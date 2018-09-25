<?php
namespace App\Routes;

use Slim\App;

class Router
{

    public static function setUp(App $app)
    {
        // routes for test
        TestsRoute::setUp($app);
    }
}
