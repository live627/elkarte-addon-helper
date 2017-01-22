<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

class MenuArea
{
    /**
     * @var string[] $permission  Array of permissions to determine who can access this area.
     * @var string   $label       Optional text string for link (Otherwise $txt[$index] will be used)
     * @var string   $file        Name of source file required for this area.
     * @var callable $function    Function to call when area is selected.
     * @var string   $custom_url  URL to use for this menu item.
     * @var string   $icon        File name of an icon to use on the menu, if using the sprite class, set as
     *      transparent.png
     * @var string   $class       Class name to apply to the icon img, used to apply a sprite icon
     * @var bool     $enabled     Should this area even be accessible?
     * @var bool     $hidden      Should this area be visible?
     * @var array    $subsections Array of subsections from this area.
     */
    public $permission = [], $label = '', $file = '', $function, $url = '', $icon = '', $class = '', $enabled = true, $hidden = false, $subsections = [];

    /**
     * @param array $arr
     *
     * @return MenuArea
     */
    public static function buildFromArray(array $arr)
    {
        $area = new self;
        $vars = get_object_vars($area);
        foreach (array_replace(
                     $vars,
                     array_intersect_key($arr, $vars)
                 ) as $var => $val) {
            $area->{$var} = $val;
        }
        if (isset($arr['subsections'])) {
            foreach ($arr['subsections'] as $var => $subsection) {
                $area->addSubsection($var, $subsection);
            }
        }

        return $area;
    }

    /**
     * @param string         $id
     * @param MenuSubsection $subsection
     *
     * @return $this
     */
    public function addSubsection($id, MenuSubsection $subsection)
    {
        $subsection = get_object_vars($subsection);
        $subsection = array_merge(
            [
                $subsection['label'],
                $subsection['permission'],
                $subsection['default'],
            ],
            $subsection
        );
        $this->subsections[$id] = $subsection;

        return $this;
    }
}
