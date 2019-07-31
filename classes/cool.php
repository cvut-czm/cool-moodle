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


use formula_x\auto_mapper;
use formula_x\parser;

defined('MOODLE_INTERNAL') || die();

class cool {

    public static function get_timetable()
    {
        require_once __DIR__.'/../lib/timetable/timetable.php';
        $options=new \timetable\options();
        return [new \timetable\timetable($options),$options];
    }
    public static function get_timetable_css()
    {
        return file_get_contents(__DIR__.'/../lib/timetable/timetable.css');
    }

    private static $formulax_loaded=false;
    public static function get_formula_x($formula) : parser
    {
        if(!self::$formulax_loaded) {
            spl_autoload_register(function($className) {
                $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
                if (strpos($className, 'formula_x') === 0) {
                    include __DIR__ . '/../lib/' . $className . '.php';
                }
            });
            self::$formulax_loaded=true;
        }
        self::set_formula_x_mapping();
        $p=new \formula_x\parser($formula);
        return $p;
    }
    private static function set_formula_x_mapping() {
        auto_mapper::set_custom_mapping([
            'math'=> ['ABS','AVG','CEIL','FLOOR','MAX','MIN','ROUND','SUM'],
            'text'=> ['CONCAT','LOWER','TRIM','UPPER'],
            'logical' => ['AND','OR','NOT','VALID','IF','FALSE','TRUE','XOR'],
            'ctu' => ['MARK'],
            'array' => ['COUNT','COUNTVALID','LAST','COUNTOCCURENCES']
        ]);
    }
}