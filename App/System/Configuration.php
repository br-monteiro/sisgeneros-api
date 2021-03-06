<?php
namespace App\System;

use HTR\Common\Json;
use App\Entities\Users;

class Configuration
{

    // App configs
    const APP_VERSION = '0.0.1';
    // WARNING: CHANGE THIS CONTENT
    const SALT_KEY = 'F342B8D84BD071DBFAB4A01FD4E54A38C18082455B9BEDD023096F04AE5B';
    const PATH_ENTITIES = 'App/Entities';
    const JSON_SCHEMA = '/home/edsonmonteiro/workspace/projects/sisgeneros/sisgeneros-api/App/json-schemes/';
    const EXPIRATE_TOKEN = 2592000; // 30 days
    const MAX_RESULTS = 50;
    const USER_ENTITY = Users::class;
    const ALLOW_CORS = [
        'localhost:8080'
    ];
    const ALLOW_HEADERS = [
        'X-PINGOTHER',
        'Content-Type',
        'Authorization'
    ];
    // Deployment
    const HOST_DEV = 'www.ceimbe.mb';
    const DATABASE_CONFIGS_DEV = [
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
        'dbname' => 'sisgeneros_mb',
        'user' => 'root',
        'password' => 'mysql-server-dev',
    ];
    // Production
    const HOST_PRD = 'www.ceimbe.mb';
    // SECURITY KEY
    // DATABASE CONFIGS
    const DATABASE_CONFIGS_PRD = [
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
        'dbname' => 'test',
        'user' => 'root',
        'password' => '',
    ];

    /**
     * Returns the configurations of htr.json files
     * @return \stdClass
     */
    public static function htrFileConfigs(): \stdClass
    {
        $projectDirectory = str_replace('App/System', '', __DIR__);
        $file = $projectDirectory . 'htr.json';

        if (file_exists($file)) {
            $object = Json::decode(file_get_contents($file));
            if (is_object($object)) {
                return $object;
            }
        }

        return new \stdClass();
    }
}
