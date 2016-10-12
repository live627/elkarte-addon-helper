<?php

/**
 * @package AddonHelper
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider as s
use live627\AddonHelper\DataValidator;
use live627\AddonHelper\Database;
use live627\AddonHelper\Dispatcher;
use Symfony\Component\HttpFoundation\Request;

class ServiceProvider implements s
{
    public function getServices()
    {
        return [
            'database' => function(ContainerInterface $container, callable $getPrevious = null) {
                return new Database;
            },
            'dispatcher' => function(ContainerInterface $container, callable $getPrevious = null) {
                return new Dispatcher;
            },
            'datavalidator' => function(ContainerInterface $container, callable $getPrevious = null) {
                return new DataValidator;
            },
            'request' => function() {
                return Request::createFromGlobals();
            }
        ];
    }
}
