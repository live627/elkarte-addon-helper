<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

use Database;

abstract class DataMapper implements DataMapperInterface
{
    /** @var Database */
    protected $db;

    /** @var EntityInterface[] */
    protected $entities = [];

    /**
     * Make "global" items available to the class
     *
     * @param object|null $db
     */
    public function __construct(Database $db = null)
    {
        $this->db = $db ?: database();
    }

    public function fetchById($id)
    {
        if (empty($this->entities[$id])) {
            $this->entities[$id] = $this->fetchBy('id = {int:id}', ['id' => $id])->current();
        }

        return $this->entities[$id];
    }

    public function save(EntityInterface $entity)
    {
        if ($entity->getId()) {
            $this->update($entity);
        } else {
            $this->insert($entity);
        }
    }

    public function insert(EntityInterface $entity)
    {
        list ($up_col, $up_data, $in_col, $in_data) = $entity->getInsertData();
        $this->db->insert(
            '',
            '{db_prefix}'.$this->tableName,
            $in_col,
            $in_data,
            ['id']
        );
                    $entity->setId($this->db->insert_id('{db_prefix}lgal_items'));
    }

    public function update(EntityInterface $entity)
    {
        list ($up_col, $up_data, $in_col, $in_data) = $entity->getInsertData();
        $this->db->query(
            '',
            '
                UPDATE {db_prefix}'.$this->tableName.'
                SET
                    '.implode(
                ',
                    ',
                $up_col
            ).'
                WHERE id = {int:current_entity}',
            $up_data
        );
    }

    public function delete(EntityInterface $entity)
    {
        $this->db->query(
            '',
            '
            DELETE FROM {db_prefix}'.$this->tableName.'
            WHERE id = {int:entity}',
            [
                'entity' => $entity->getId(),
            ]
        );
    }
}
