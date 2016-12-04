<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

use Database;

abstract class DataMapper implements DataMapperInterface
{
    /** @var Database */
    protected $db;

    /**
     * Make "global" items available to the class
     *
     * @param object|null $db
     */
    public function __construct(Database $db = null)
    {
        $this->db = $db ?: database();
    }
}
