<?php

/**
 * @package AddonHelper
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

abstract class BitwiseFlag
{
    protected $flags = 0x0;

    /*
     * Note: these functions are protected to prevent outside code
     * from falsely setting BITS.
     */

    protected function __construct($flags = 0x0)
    {
        $this->flags = Sanitizer::sanitizeInt($flags, 0x0, 0x80000000);
    }

    protected function isFlagSet($flag)
    {
        return ($this->flags & $flag) == $flag;
    }

    protected function setFlag($flag, $value)
    {
        if ($value) {
            $this->flags |= $flag;
        } else {
            $this->flags &= ~$flag;
        }
    }

    /*
     * Returns the stored bits.
     *
     * @access public
     * @return int
     */
    public function __toString()
    {
        return (string) $this->flags;
    }
}
