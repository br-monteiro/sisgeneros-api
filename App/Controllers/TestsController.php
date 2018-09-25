<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\System\Configuration as cfg;

class TestsController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function home(Request $request, Response $response): Response
    {
        $object =  new \stdClass();
        $object->message = "here work is ok =)";
        $object->status = "success";
        $object->data = cfg::htrFileConfigs();
        return $response->withJson($object);
    }
}
