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
 * Course category entity
 *
 * @package local_cool
 * @category entity
 * @copyright 2018 CVUT CZM, Jiri Fryc
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cool\entity;

defined('MOODLE_INTERNAL') || die();

class course_category extends database_entity {
    const TABLENAME = 'course_categories';

    protected $name;
    protected $idnumber;
    protected $description;
    protected $descriptionformat;
    protected $parent;
    protected $sortorder;
    protected $coursecount;
    protected $visible;
    protected $visibleold;
    protected $timemodified;
    protected $depth;
    protected $path;
    protected $theme;

    public function get_courses() : array {
        return course::get_all(['category' => $this->id]);
    }

    /**
     * Shortcut to creating root category
     *
     * We don´t need much more becouse we don´t order by moodle native categories.
     *
     * @return course_category
     */
    public static function create_root_category(string $name, string $idnumber, string $description='') : course_category {
        $entity = new course_category();
        $entity->name = $name;
        $entity->idnumber = $idnumber;
        $entity->description = $description;
        $entity->descriptionformat = 0;
        $entity->parent = 0;
        $entity->coursecount = 0;
        $entity->visible = 1;
        $entity->visibleold = 1;
        $entity->timemodified = time();
        $entity->depth = 1;
        $entity->theme = null;
        $entity->path = '';
        $entity->save();

        /* Only way how to make path field.
         * I refuse to use database magic methods
         * for getting and reserving new primary key from sequence generator.
         */
        $entity->path = '/' . $entity->id;
        $entity->save();

        return $entity;
    }

}