<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

use DomainException;

/**
 * Class Menu
 * @package live627\AddonHelper
 */
class Menu
{
    /**
     * @var Collection
     */
    /**
     * @var Collection
     */
    private $options, $sections;
    /**
     * @var Controller
     */
    /**
     * @var array|bool
     */
    /**
     * @var bool
     */
    private $obj, $incData = [], $doLinktree = true;

    /**
     *
     */
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

    /**
     * @throws DomainException
     */
    public function execute()
    {
        $this->createMenu();

        // Nothing valid?
        if ($this->incData == false) {
            throw new DomainException('Cannot build menu: invalid array.');
        }
        if ($this->doLinktree) {
            $this->buildLinktree();
        }

        callMenu($this->incData);
    }

    /**
     *
     */
    private function buildLinktree()
    {
        global $context;

        $linktree = $this->obj->getContainer()->get('linktree')->add(
            $this->obj->text('title'),
            $this->obj->scriptUrl.'?action='.$context['current_action']
        );
        if (isset($this->incData['current_area'])) {
            $linktree->add(
                $this->incData['label'],
                $this->obj->scriptUrl.'?action='.$context['current_action'].';area='.$this->incData['current_area']
            );
        }
        if (current(array_keys($this->incData['subsections'])) != $this->incData['current_subsection']) {
            $linktree->add(
                $this->incData['subsections'][$this->incData['current_subsection']][0],
                $this->obj->scriptUrl.'?action='.$context['current_action'].';area='.$this->incData['current_area'].';sa='.$this->incData['current_subsection']
            );
        }
        $linktree->execute();
    }

    /**
     * @param             $id
     * @param MenuSection $section
     *
     * @return $this
     */
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

    /**
     * @param Controller $obj
     * @param bool       $doLinktree
     */
    public function __construct(Controller $obj, $doLinktree = true)
    {
        $this->obj = $obj;
        $this->doLinktree = $doLinktree;
        $this->options = new Collection;
        $this->sections = new Collection;
    }
}
