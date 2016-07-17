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

        // Default to sub action 'index'
        if (!isset($_GET['sa']) || !isset($obj->subActions[$_GET['sa']])) {
            $_GET['sa'] = 'index';
        }
        $thisSubAction = $obj->subActions[$_GET['sa']];

        // This area is wide open.
        loadTemplate('AddonHelper');
        if (isset($_GET['area']) && is_callable('template_' . $_GET['area'] . '_' . $_GET['sa'])) {
            $context['sub_template'] = $_GET['area'] . '_' . $_GET['sa'];
        } else {
            $context['sub_template'] = $_GET['sa'];
        }

        // Preemptively set a page title.
        $context['page_title'] = $obj->text('title');

        // This area is reserved for admins only - do this here since the menu code does not.
        isAllowedTo($thisSubAction[1]);

        // Calls a private function based on the sub-action
        $obj->$thisSubAction[0]();
    }
}
