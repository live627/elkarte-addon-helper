<?php

/**
 * @package AddonHelper
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

class Menu
{
    private $options, $sections;
    private $obj, $incData = [], $doLinktree=true;

    public function createMenu()
    {
        $options = [];
        $sections = [];

        foreach ($this->options as list ($id, $item)) {
            $options[$id] = $item;
        }
        foreach ($this->sections as list ($id, $item)) {
            $sections[$id] = $item;
        }

        require_once(SUBSDIR.'/Menu.subs.php');
        $this->incData = createMenu($sections, $options);
    }

    public function execute()
    {
        $this->createMenu();

        // Nothing valid?
        if ($this->incData == false) {
            throw new \Elk_Exception('no_access', false);
        }
        if ($this->doLinktree) {
            $this->buildLinktree();
        }

        callMenu($this->incData);
    }

    private function buildLinktree()
    {
        $linktree = $this->obj->getContainer()->get('linktree')->add(
            $this->obj->text('title'), $this->obj->scriptUrl.'?action='.$context['current_action']);
        if (isset($this->incData['current_area']) && $this->incData['current_area'] != 'index') {
            $linktree->add($this->incData['label'],
                $this->obj->scriptUrl.'?action='.$context['current_action'].';area='.$this->incData['current_area']);
        }
        if (!empty($this->incData['current_subsection']) && $this->incData['subsections'][$this->incData['current_subsection']][0] != $this->incData['label']) {
            $linktree->add($this->incData['subsections'][$this->incData['current_subsection']][0],
                $this->obj->scriptUrl.'?action='.$context['current_action'].';area='.$this->incData['current_area'].';sa='.$this->incData['current_subsection']);
        }
        $linktree->execute();
    }

    public function addSection($id, MenuSection $section)
    {
        $this->sections->add([$id, get_object_vars($section)]);

        return $this;
    }

    /**
     * Adds an option to the menu
     *
     * @access public
     * @return void
     */
    public function addOption($id, $val)
    {
        $this->options->add([$id, $val]);
    }

    public function __construct(Ohara $obj, $doLinktree=true)
    {
        $this->obj=$obj;
        $this->doLinktree=$doLinktree;
        $this->options = new Collection;
        $this->sections = new Collection;
    }
}
