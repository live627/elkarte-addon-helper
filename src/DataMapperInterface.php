<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

/**
 * Interface DataMapperInterface
 * @package live627\AddonHelper
 */
interface DataMapperInterface
{
    /**
     * @param $id int
     *
     * @return EntityInterface
     */
    public function fetchById($id);

    /**
     * @param int   $where
     * @param array $where_vars
     *
     * @return EntityInterface[]
     */
    public function fetchBy($where = '', array $where_vars = []);

    /**
     * @param EntityInterface $entity
     *
     * @return int
     */
    public function insert(EntityInterface $entity);

    /**
     * @param EntityInterface $entity
     *
     * @return mixed
     */
    public function update(EntityInterface $entity);

    /**
     * @param EntityInterface $entity
     *
     * @return mixed
     */
    public function save(EntityInterface $entity);

    /**
     * @param EntityInterface $entity
     */
    public function delete(EntityInterface $entity);
}
