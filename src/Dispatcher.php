<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

class Dispatcher
{
    public function dispatch(Ohara $obj)
    {
        global $context;

        $request = $obj->getContainer()->get('requestStack')->getCurrentRequest();
        $sa = $request->query->get('sa');
        $area = $request->query->get('area');

        // Always fallback to sub action 'index'.
        if (!is_callable([$obj, 'action'.ucfirst($sa)])) {
            $sa = 'index';
        }

        // This area is wide open.
        $this->loadSubTemplate($area, $sa);
        $this->setTabData($obj, $sa);

        // Preemptively set a page title.
        $context['page_title'] = $obj->text('title');

        $this->callSubAction($obj, $sa);
    }

    /**
     * Load up all the tabs.
     *
     * @param Ohara  $obj
     * @param string $sa
     */
    private function setTabData(Ohara $obj, $sa)
    {
        global $context;

        $context['menu_data_'.$context['max_menu_id']]['tab_data'] = [
            'title' => $obj->text('title'),
            'description' => $obj->text('desc'),
            $sa => [
                'description' => $obj->text($sa.'_desc'),
            ],
        ];
    }

    /**
     * Call a function based on the sub-action.
     * Also ensure that the action is allowed.
     *
     * @param Ohara  $obj
     * @param string $sa
     */
    private function callSubAction(Ohara $obj, $sa)
    {
        // This area is reserved - do this here since the menu code does not.
        if (false===$obj->getServiceLayer()->checkAccess($sa)) {
            throw new \Elk_Exception('no_access', false);
        }

        $obj->{'action'.ucfirst($sa)}();
    }

    /**
     * Load a sub-template.
     *
     * @param string $area
     * @param string $sa
     */
    private function loadSubTemplate($area, $sa)
    {
        global $context;

        loadTemplate('LiveGallery');
        if (!empty($area) && is_callable('template_'.$area.'_'.$sa)) {
            $context['sub_template'] = $area.'_'.$sa;
        } else {
            $context['sub_template'] = $sa;
        }
    }
}
