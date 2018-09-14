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
 * Course entity
 *
 * @package local_cool
 * @category entity
 * @copyright 2018 CVUT CZM, Jiri Fryc
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cool\entity;

defined('MOODLE_INTERNAL') || die();

class course extends database_entity {
    /**
     * Name of database table for entity.
     */
    const TABLENAME = 'course';

    protected $category;
    protected $sortorder;
    protected $fullname;
    protected $shortname;
    protected $idnumber;
    protected $summary;
    protected $summaryformat;
    protected $format;
    protected $showgrades;
    protected $newsitems;
    protected $startdate;
    protected $enddate;
    protected $marker;
    protected $maxbytes;
    protected $legacyfiles;
    protected $showreports;
    protected $visible;
    protected $visibleold;
    protected $groupmode;
    protected $groupmodeforce;
    protected $defaultgroupingid;
    protected $lang;
    protected $calendartype;
    protected $theme;
    protected $timecreated;
    protected $timemodified;
    protected $requested;
    protected $enablecompletion;
    protected $completionnotify;
    protected $cacherev;

    public function get_time_created(): int {
        return (int) $this->timecreated;
    }

    public function set_shortname(string $newname): course {
        $this->shortname = $newname;
        return $this;
    }

    public function get_shortname(): string {
        return $this->shortname;
    }

    public function set_fullname(string $newname): course {
        $this->fullname = $newname;
        return $this;
    }

    public function get_fullname(): string {
        return $this->fullname;
    }

    public function set_visibility(bool $visibility): course {
        $this->visible = $visibility ? '1' : '0';
        $this->visibleold = $this->visible;
        return $this;
    }

    public function get_visibility(): bool {
        return $this->visible == '1';
    }

    public function set_category_id(int $category): course {
        $this->category = $category;
        return $this;
    }

    public function set_category(course_category $category): course {
        $this->category = $category->get_id();
        return $this;
    }

    public function get_category_id(): int {
        return (int) $this->category;
    }

    public function get_category(): course_category {
        return course_category::get($this->category);
    }

    public function set_idnumber(string $idnumber): course {
        $this->idnumber = $idnumber;
        return $this;
    }

    public function get_idnumber(): string {
        return $this->idnumber;
    }

    public function get_context() : \context_course {
        return \context_course::instance($this->id);
    }

}