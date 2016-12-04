<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

use Interop\Container\ContainerInterface;

interface ControllerInterface
{
    /**
     * Default action.
     *
     * @usedby Dispatcher
     * @return string
     */
    public function actionIndex();

    /**
     * @return ContainerInterface
     */
    public function getContainer();

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container);

    public function getServiceLayer();
}
