<?php

/**
 * @package AddonHelper
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

class Linktree
{
    private $collection;

    public function execute()
    {
        global $context;

        foreach ($this->collection as list ($name, $url, $before, $after)) {
            $item = array(
                'name' => $name,
                'url' => $url,
                'extra_before' => $before,
                'extra_after' => $after
            );

            $context['linktree'][] = $item;
        }
    }

    public function add($name, $url = null, $before = null, $after = null)
    {
        $this->collection->add([$name, $url, $before, $after]);

        return $this;
    }

    public function __construct()
    {
        $this->collection = new Collection;
    }

}
