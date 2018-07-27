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

class css_builder {
    private $content = '';
    private $preselector = '';

    public function pre_selector(string $preselector) : css_builder {
        $this->preselector=$preselector;
        return $this;
    }

    public static function get(): css_builder {
        return new css_builder();
    }

    public function build(): string {
        $o = $this->content;
        $this->content = '';
        return $o;
    }

    public function selector($selector): css_builder {
        $this->content .= PHP_EOL . $this->preselector . $selector;
        return $this;
    }

    public function tag_start(): css_builder {
        $this->content .= PHP_EOL . '{';
        return $this;
    }

    public function empty_line(): css_builder {
        $this->content .= PHP_EOL;
        return $this;
    }

    public function tag_end(): css_builder {
        $this->content .= PHP_EOL . '}';
        return $this;
    }

    public function setting(string $name, string $value, bool $important = false): css_builder {
        $this->content .= PHP_EOL . '    ' . $name . ' : ' . $value . ($important ? '!important' : '') . ';';
        return $this;
    }

    public function settings(array $values): css_builder {
        foreach ($values as $k => $v) {
            $this->content .= PHP_EOL . '    ' . $k . ' : ' . $v . ';';
        }
        return $this;
    }
}