<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

class MenuSubsection
{
    /**
     * @var string   $label       Text label for this subsection.
     * @var string[] $permission  Array of permissions to check for this subsection.
     * @var bool     $default     Is this the default subaction - if not set for any will default to first...
     * @var bool     $enabled     Bool to say whether this should be enabled or not.
     * @var string[] $active      Set the button active for other subsections.
     */
    public $label = '', $permission = [], $enabled = true, $default = false, $active = [];

    /**
     * MenuSubsection constructor.
     *
     * @param string $label
     * @param string[] $permission
     * @param bool   $enabled
     * @param bool   $default
     * @param string[] $active
     */
    public function __construct($label, array $permission, $enabled = true, $default = false, array $active = [])
    {
        $this->label = $label;
        $this->permission = $permission;
        $this->enabled = $enabled;
        $this->default = $default;
        $this->active = $active;
    }
}
