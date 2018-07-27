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

class day {
    /** @var record[] $records */
    private $records = [];

    public function add_record(record $record) {
        $this->records[] = $record;
    }

    /** @return line[] */
    public function line_records(timetable_options $options) {
        uasort($this->records, function(record $a, record $b) {
            $i = $a->start_time[0] - $b->start_time[0];
            $e = $a->start_time[1] - $b->start_time[1];
            return $i == 0 ? $e : $i;
        });
        $lines = [];
        $line = new line(new empty_record(0, 0));
        foreach ($this->records as $record) {
            if ($line->is_in($record)) {
                $line->add_record($record);
            } else {
                if ($line->last_end() != $record->start_time) {
                    $line = new line(new empty_record($line->last_end(), $record->start_time));
                    $lines[] = $line;
                }
                $line = new line($record);
                $lines[] = $line;
            }
        }
        return $lines;
    }
}

class line {
    public $parallel = [];
    public $last_end = [];

    public function last_end() {
        $l = $this->last_end[0];
        foreach ($this->last_end as $k) {
            if ($k > $l) {
                $l = $k;
            }
        }
        return $l;
    }

    public function print(timetable_options $options): string {
        $size = $this->last_end() - $this->parallel[0][0]->start_time;
        $output = '<div class="flex-g-' . $size . '">';
        foreach ($this->parallel as $parallel) {
            foreach ($parallel as $course) {
                $output .= $options->get_record_type($course->type)->render($size, $course->data);
            }
        }
        $output .= '</div>';
        return $output;
    }

    public function __construct(record $record) {
        $this->parallel[] = [$record];
        $this->last_end[] = $record->end_time;
    }

    public function add_record(record $record) {
        for ($i = 0; $i < count($this->parallel); $i++) {
            $time = $this->last_end[$i] - $record->start_time;
            if ($time <= 0) {
                if ($time < 0) {
                    $empty = new empty_record($this->last_end[$i], $record->start_time);
                    $this->parallel[$i][] = $empty;
                }
                $this->parallel[$i][] = $record;
                $this->last_end[$i] = $record->end_time;
                return;
            }
        }
        $this->parallel[] = [new empty_record($this->parallel[0][0]->start_time, $record->start_time), $record];
        $this->last_end[] = $record->end_time;
    }

    public function is_in(record $record): bool {
        return $this->last_end() > $record->start_time;
    }
}