<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

class NullEntity implements EntityInterface
{
    /**
     * @return int
     */
    public function getId()
    {
        return 0;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }

    /**
     * @return array
     */
    public function getInsertData()
    {
        return [];
    }

    /**
     * @param string $permission
     *
     * @access public
     * @return bool
     */
    public function allowedTo($permission)
    {
        return true;
    }
}
