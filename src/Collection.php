<?php

/**
 * @package AddonHelper
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

class Collection implements \IteratorAggregate
{
    /**
     * The array that holds all the items collected by the object.
     *
     * @var array
     * @access private
     */
    private $items = array();

    /**
     * Appends a value to the object
     *
     * This method is chainable.
     *
     * @param mixed  The value to set
     * @access public
     * @return Collection
     */
    public function add($item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Retrieve an external iterator.
     *
     * @access public
     * @abstracting IteratorAggregate
     * @return Generator
     */
    public function getIterator()
    {
        foreach ($this->items as $item) {
            yield $item;
        }
    }
}
