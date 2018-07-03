<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Database entity
 *
 * @package local_cool
 * @category entity
 * @copyright 2018 CVUT CZM, Jiri Fryc
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cool\entity;

defined('MOODLE_INTERNAL') || die();

class database_entity {
    // region Static private context.
    private static $_object_cache = [];

    /**
     * Mapper between \stdClass and dbentity.
     *
     * @param \stdClass|static $from
     * @param \stdClass|static $to
     * @param \array $mappedvars
     * @return \stdClass|static
     */
    protected static function mapper($from, $to, $mappedvars) {
        if (!isset($from) || $from == false) {
            return null;
        }
        foreach ($mappedvars as $value) {
            if (isset($from->{$value}) && ($value !== 'id' || $from->{$value} != -1)) {
                $to->{$value} = $from->{$value};
            }
        }
        $to = static::additional_mapper($from, $to);
        return $to;
    }

    protected static function additional_mapper($from, $to) {
        return $to;
    }

    /**
     * Convert dbentity to \stdClass.
     *
     * @return \stdClass
     */
    private function to_std_class(): \stdClass {
        return self::mapper($this, new \stdClass(), $this->mapped_vars());
    }

    // endregion.

    /**
     * Name of database table for entity.
     */
    const TABLENAME = 'undefined';

    /**
     * All tables should be indexed by id. As per moodle specification.
     *
     * @var int
     */
    protected $id;

    /**
     * Variables that will be mapped to/from database.
     *
     * In default all public variables is taken.
     *
     * @return string[]|null
     */
    protected function mapped_vars() : ? array {
        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
        $vars = [];
        foreach ($props as $prop) {
            $vars[] = $prop->name;
        }
        return $vars;
    }

    /**
     * Variables/columns that are indexed and should be cached by this index.
     *
     * @return array
     */
    protected function index_columns() : array {
        return ["id"];
    }


    // region Public static functions.

    /**
     * Finds if atleast one record, that match criteria, exist.
     *
     * @param string|int|array $id
     * @param string $col
     * @return bool
     * @throws \dml_exception
     */
    public static function exist($arguments, string $col = 'id'): bool {
        global $DB;
        if (is_array($arguments)) {
            return $DB->record_exists(static::TABLENAME, $arguments);
        } else {
            return $DB->record_exists(static::TABLENAME, array($col => $arguments));
        }
    }

    /**
     * Count all entities matching criteria
     *
     * @param array|null|string|int $arguments
     * @param string $col
     * @return integer
     * @throws \dml_exception
     */
    public static function count(array $arguments = null, string $col = 'id'): int {
        global $DB;
        if (is_array($arguments)) {
            return $DB->count_records(static::TABLENAME, $arguments);
        } else {
            return $DB->count_records(static::TABLENAME, array($col => $arguments));
        }
    }

    /**
     * Delete all entities matching criteria.
     *
     * @param string|string[] $arguments
     * @param string $col
     * @throws \dml_exception
     */
    public static function delete($arguments, string $col = 'id'): void {
        global $DB;
        if (is_array($arguments)) {
            $DB->delete_records(static::TABLENAME, $arguments);
        } else {
            $DB->delete_records(static::TABLENAME, array($col => $arguments));
        }
    }

    /**
     * Get entity from database.
     *
     * @param array|string|int $arguments Single field or Dictionary<string,string>
     * @param string $col Column for single field in $arguments.
     * @param string $cachename Cache_name caller
     * @param static[]|static|null $data
     * @return static|null
     * @throws \dml_exception
     */
    public static function get($arguments, string $col = 'id', string $cachename = null, $data = null) {
        global $DB;
        $reflection = new \ReflectionClass(static::class);
        $entity = $reflection->newInstance();
        if (is_array($arguments)) {
            $record = $DB->get_record(static::TABLENAME, $arguments);
        } else {
            $record = $DB->get_record(static::TABLENAME, array($col => $arguments));
        }
        $entity = self::mapper($record, $entity, $entity->mapped_vars());
        if ($entity === null) {
            return null;
        }
        return $entity;
    }

    /**
     * Get entity field that match criteria.
     *
     * @param array $arguments
     * @param string $field
     * @return string
     * @throws \dml_exception
     */
    public static function get_field($arguments, string $field): string {
        global $DB;
        return $DB->get_field(static::TABLENAME, $field, $arguments);
    }

    /**
     * Get all entities that match criteria.
     *
     * @param array $arguments
     * @param string $cachename
     * @param mixed $data
     * @return static[]
     * @throws \dml_exception
     */
    public static function get_all(array $arguments = [], string $cachename = null, $data = null): array {
        global $DB;
        $reflection = new \ReflectionClass(static::class);
        $entities = array();
        $records = $DB->get_records(static::TABLENAME, $arguments);
        foreach ($records as $record) {
            $entity = $reflection->newInstance();
            $entity = self::mapper($record, $entity, $entity->mapped_vars());
            if ($entity == null) {
                continue;
            }
            $entities[] = $entity;
        }
        return $entities;
    }

    // endregion.

    // region Public functions.
    public function remove_from_db(): bool {
        global $DB;
        if ($this->id >= 0) {
            try {
                $DB->delete_records(static::TABLENAME, array('id' => $this->id));
                return true;
            } catch (\dml_exception $exception) {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Save entity to database.
     *
     * If this is new entity, then inserts it.
     * If this is already existing entity (in database context), then it will update it´s fields.
     *
     * @throws \dml_exception
     */
    public function save(): void {
        global $DB;
        $object = $this->to_std_class();
        if (isset($object->id)) {
            $DB->update_record(static::TABLENAME, $object);
        } else {
            $this->id = $DB->insert_record(static::TABLENAME, $object);
        }
    }

    public function get_id(): int {
        return $this->id;
    }

    // endregion.

}