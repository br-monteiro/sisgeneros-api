<?php
namespace App\System;

use HTR\Common\DefaultContainer;
use Slim\Container;

class AppContainer extends DefaultContainer
{

    /**
     * Returns the Container Object configured
     *
     * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
     * @return Slim\Container
     */
    public static function container(): Container
    {
        $container = parent::container();
        /**
         * Put here your container configurations. Example:
         * $container['settings']['displayErrorDetails'] = false;
         */
        return $container;
    }
}
