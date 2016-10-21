<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

class MenuSection
{
    /**
     * @var string   $title      Section title.
     * @var bool     $enabled    Should section be shown?
     * @var string[] $permission Permissions required to access the whole section
     * @var array    $areas      Array of areas within this section.
     */
    public $title = '', $enabled = true, $permissions = [], $areas = [];

    public static function buildFromArray(array $arr)
    {
        $section = new self;
        foreach ($arr as $var => $val)
        {
            if (property_exists($section, $var))
                $section->{$var} = $val;
        }
        if (isset($arr['areas']))
        foreach ($arr['areas'] as $var => $area)
        {
            $section->addArea($var, $area);
        }

        return $section;
    }

    public function addArea($id, MenuArea $area)
    {
        $this->areas[$id] = get_object_vars($area);

        return $this;
    }
}
