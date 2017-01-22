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

    /**
     * @param array $arr
     *
     * @return MenuSection
     */
    public static function buildFromArray(array $arr)
    {
        $section = new self;
        $vars = get_object_vars($section);
        foreach (array_replace(
                     $vars,
                     array_intersect_key($arr, $vars)
                 ) as $var => $val) {
            $section->{$var} = $val;
        }
        if (isset($arr['areas'])) {
            foreach ($arr['areas'] as $var => $area) {
                $section->addArea($var, $area);
            }
        }

        return $section;
    }

    /**
     * @param string   $id
     * @param MenuArea $area
     *
     * @return $this
     */
    public function addArea($id, MenuArea $area)
    {
        $this->areas[$id] = get_object_vars($area);

        return $this;
    }
}
