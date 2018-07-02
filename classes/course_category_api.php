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
 * Course category api
 *
 * @package local_cool
 * @category core
 * @copyright 2018 CVUT CZM, Jiri Fryc
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cool;

defined('MOODLE_INTERNAL') || die();

namespace local_cool;

class course_category_api {
    private const TABLE = 'course_categories';

    public static function get_or_create_category(string $name, ? string $idnumber = null,
            ? int $parent = null) : course_category_api {
        $data = new \stdClass();
        $data->name = $name;
        if ($idnumber !== null) {
            $data->idnumber = $idnumber;
        }
        if ($parent !== null) {
            $data->parent = $parent;
        }
        \coursecat::create($data);
    }

    public static function get_by_idnumber(string $idnumber) : course_category_api {
        global $DB;
        return new course_category_api($DB->get_record(self::TABLE, ['idnumber' => $idnumber]));
    }

    public static function get_by_name(string $name, int $parent = 0) {
        global $DB;
        return new course_category_api($DB->get_record(self::TABLE, ['name' => $name, 'parent' => $parent]));
    }

    private $data;

    public function __construct($data) {
        $this->data = $data;
    }
}