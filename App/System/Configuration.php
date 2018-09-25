<?php
namespace App\System;

use HTR\Common\Json;

class Configuration
{

    // App configs
    const APP_VERSION = '0.0.1';
    // WARNING: CHANGE THIS CONTENT
    const SALT_KEY = 'C203DFCDFF90245C838C18082455B9BEDD023EB726001292053849394CE44304';
    const PATH_ENTITIES = 'App/Entities';
    const JSON_SCHEMA = '../App/json-schema/';
    const EXPIRATE_TOKEN = 2592000; // 30 days
    const ALLOW_CORS = [];
    const ALLOW_HEADERS = [
        'X-PINGOTHER',
        'Content-Type',
        'Authorization'
    ];
    // Deployment
    const HOST_DEV = 'localhost';
    const DATABASE_CONFIGS_DEV = [
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
        'dbname' => 'test',
        'user' => 'root',
        'password' => 'mysql-server-dev',
    ];
    // Production
    const HOST_PRD = 'localhost';
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
