<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

use UnexpectedValueException;

class Dispatcher
{
    public function dispatch(Controller $obj)
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

        $this->callSubAction($obj, $sa, $request);
    }

    /**
     * Load up all the tabs.
     *
     * @param Controller $obj
     * @param string     $sa
     */
    private function setTabData(Controller $obj, $sa)
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
     * @param Controller $obj
     * @param string     $sa
     */
    private function callSubAction(Controller $obj, $sa, $request)
    {
        // This area is reserved.
        if (false === $obj->getServiceLayer()->checkAccess($request)) {
            throw new UnexpectedValueException('Accesss denied.');
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
