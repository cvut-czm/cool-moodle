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

class record_type {

    public $id;
    public $bgcolor;
    public $fgcolor;

    public function __construct(string $id, string $bgcolor, string $fgcolor = 'black') {
        $this->id = $id;
        $this->bgcolor = $bgcolor;
        $this->fgcolor = $fgcolor;
    }
    public function render($size,$data)
    {
        if($this->id=='empty')
            return '<div class="flex-g-'.$size.'"></div>';
        return '<div class="record flex-g-'.$size.' '.$this->id.'">'.$data[0].'</div>';
    }

    /**
     * @return record_type[]
     */
    public static function get_defaults(): array {
        return [
                new record_type('lecture', '#87CEFA'),
                new record_type('tutorial', '#7FFF00'),
                new record_type('empty','#000000','#000000')
        ];
    }
}