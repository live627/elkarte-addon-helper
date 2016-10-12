<?php

/**
 * @package AddonHelper
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

class Dispatcher
{
    public function dispatch(Ohara $obj)
    {
        global $context;

        // Load up all the tabs...
        $context[$context['admin_menu_name']]['tab_data'] = array(
            'title' => $obj->text('title'),
            'description' => $obj->text('desc'),
        );
        $request = $obj->getContainer()->get('request');
        $sa = $request->query->get('sa');
        $area = $request->query->get('area');

        // Default to sub action 'index'
        if (!isset($obj->subActions[$sa])) {
            $sa = 'index';
        }
        $thisSubAction = $obj->subActions[$sa];

        // This area is wide open.
        loadTemplate('LiveGallery');
        if (!empty($area) && is_callable('template_'.$area.'_'.$sa)) {
            $context['sub_template'] = $area.'_'.$sa;
        } else {
            $context['sub_template'] = $sa;
        }

        // Preemptively set a page title.
        $context['page_title'] = $obj->text('title');

        // This area is reserved - do this here since the menu code does not.
        if (!empty($thisSubAction[1]))
            isAllowedTo($thisSubAction[1]);

        // Calls a private function based on the sub-action
        $obj->{$thisSubAction[0]}();
    }
}
