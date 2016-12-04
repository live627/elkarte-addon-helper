<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

interface DataMapperInterface
{
    public function fetchById($id);
    public function fetchBy($where, array $where_vars = []);
    public function insert(EntityInterface $entity);
    public function update(EntityInterface $entity);
    public function save(EntityInterface $entity);
    public function delete(EntityInterface $entity);
}
