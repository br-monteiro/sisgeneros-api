<?php
// bootstrap.php

require_once "vendor/autoload.php";

use App\System\Configuration as cfg;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

$isDevMode = true;
$paths = [getcwd() . '/' . cfg::PATH_ENTITIES];

// the connection configuration
$cache = new ArrayCache();
$reader = new AnnotationReader();
$driver = new AnnotationDriver($reader, $paths);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);
$config->setMetadataDriverImpl($driver);

$entityManager = EntityManager::create(cfg::DATABASE_CONFIGS_DEV, $config);
