<?php

/**
 * @package AddonHelper
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

use Interop\Container\ServiceProvider as s;
use Interop\Container\ContainerInterface;
use live627\AddonHelper\Ohara;
use live627\AddonHelper\Dispatcher;
use live627\AddonHelper\DataValidator;
use live627\AddonHelper\Database;
use HttpReq;
use oNeDaL\Loggers\SqliteLogger;
use Psr\Log\LogLevel;
use PHPExtra\EventManager\EventManager;

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
            'httpreq' => function(ContainerInterface $container, callable $getPrevious = null) {
                $dependency = $container->get('datavalidator');
                return new HttpReq($dependency);
            },
            'logger' => function(ContainerInterface $container, callable $getPrevious = null) {
                return $this->registerLogger($container, $getPrevious);
            },
            'eventmanager' => function(ContainerInterface $container, callable $getPrevious = null) {
                $dependency = $container->get('logger');
                $em = new EventManager;
                $em->setLogger($dependency);
                return $em;
            },
            'uploaddir' => function(ContainerInterface $container, callable $getPrevious = null) {
                return BOARDDIR . '/addons/AddonHelper/uploads';
            }
        ];
    }

    private function registerLogger(ContainerInterface $container, callable $getPrevious = null) {
        //~ $uploadDir = BOARDDIR . '/addons/AddonHelper/uploads';
        $config = array('db_path' => $container->get('uploaddir') . '/application.sqlite');
        // create instance of new logger
        $logger = new SqliteLogger('BasicLogExampleApp', $config);
        //~ $logger->setMinimalLevel(LogLevel::INFO);
        // create log table if is not available
        if (!$logger->logTableExists()) {
            $logger->createLogTable();
        }
        // this message is not logged, because minimal log level is set to 'INFO'
        $logger->debug('This message is not logged.');
        // add first
        $logger->info('Starting logger.', array('time' => time()));

        return $logger;
    }
}
