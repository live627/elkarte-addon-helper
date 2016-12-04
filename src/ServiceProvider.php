<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

use Interop\Container\ServiceProvider as s;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

class ServiceProvider implements s
{
    public function getServices()
    {
        return [
            'linktree' => function () {
                return new Linktree;
            },
            'dispatcher' => function () {
                return new Dispatcher;
            },
            'datavalidator' => function () {
                return new DataValidator;
            },
            'requestStack' => function () {
                $requestStack = new RequestStack;
                $requestStack->push(Request::createFromGlobals());

                return $requestStack;
            },
        ];
    }
}
