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
    /**
     * @return EntityInterface
     */
    public function getDataFromPost();

    /**
     * @return array
     */
    public function validatePostData();

    /**
     * @return EntityInterface
     */
    public function getEntityFromQueryString();

    /**
     * @param string $sa
     *
     * @return bool
     */
    public function checkAccess($sa);
}
