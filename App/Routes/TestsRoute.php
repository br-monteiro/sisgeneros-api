<?php
namespace App\Routes;

use Slim\App;
use App\Controllers\TestsController;

class TestsRoute
{

    public static function setUp(App $app)
    {
        $app->get('/', TestsController::class . ":home");
    }
}
