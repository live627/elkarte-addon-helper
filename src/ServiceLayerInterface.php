<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

interface ServiceLayerInterface
{
    public function getDataFromPost();

    public function validatePostData();

    public function getEntityFromQueryString();

    /**
     * @param string $sa
     *
     * @return bool
     */
    public function checkAccess($sa);
}
