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

namespace local_cool;

defined('MOODLE_INTERNAL') || die();

class multilang {

    public static function for_plugin(string $plugin) : multilang
    {
        return new multilang($plugin);
    }
    private $plugin;
    public function __construct(string $plugin) {
        $this->plugin=$plugin;
    }

    public function by_key(string $key) : string {
        $cs=new \lang_string($key,$this->plugin);
        return '<span class="multilang" lang="en">'.$cs->out('en').'</span><span lang="cs" class="multilang">'.$cs->out('cs').'</span>';
    }
}