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
 * This is a one-line short description of the file.
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    xxxxxx
 * @category   xxxxxx
 * @copyright  2018 CVUT CZM, Jiri Fryc
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cool\timetable;

defined('MOODLE_INTERNAL') || die();

class timetable_options {
    public $days = [0, 1, 2, 3, 4];
    public $time_start = [7, 0];
    public $time_end = [20, 0];
    public $lowest_increment = 15;
    /** @var record_type[] $record_types */
    public $record_types = [];

    public function get_record_type(string $id) {
        foreach ($this->record_types as $type)
        {
            if($type->id==$id)
                return $type;
        }
        return null;
    }

    public function elements(): int {
        $st = $this->time_start;
        $st = $st[0] * 60 + $st[1];
        $en = $this->time_end;
        $en = $en[0] * 60 + $en[1];
        $inc = $this->lowest_increment;
        return ($en - $st) / $inc;
    }

    public function set_days_and_order(array $order): timetable_options {
        $this->days = $order;
        return $this;
    }

    private function __construct() {
        $this->record_types = record_type::get_defaults();
    }

    public static function create(): timetable_options {
        return new timetable_options();
    }
}