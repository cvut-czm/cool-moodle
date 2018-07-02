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
 * Config plugin entity
 *
 * @package local_cool
 * @category entity
 * @copyright 2018 CVUT CZM, Jiri Fryc
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cool\entity;

defined('MOODLE_INTERNAL') || die();


class config_plugin extends database_entity {
    const TABLENAME = 'config_plugins';

    protected $plugin;
    protected $name;
    protected $value;

    public static function get_or_create(string $plugin, string $name, $default = null) {
        $entity = null;
        try {
            $entity = self::get(['plugin' => $plugin, 'name' => $name]);
        } catch (\dml_exception $e) {
            $entity = null;
        }
        if ($entity == null) {
            $entity = new config_plugin();
            $entity->plugin = $plugin;
            $entity->name = $name;
            $entity->value = $default;
            $entity->save();
        }
        return $entity->value;
    }
}