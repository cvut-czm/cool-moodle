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

require_once('../../config.php');

$timetable = new \local_cool\timetable\timetable(\local_cool\timetable\timetable_options::create());
foreach ([
        0 => [
                [[9, 15], [10, 45], ['SWA'], 'lecture'],
                [[12, 45], [14, 15], ['ESW'], 'lecture'],
        ],
        2 => [
                [[11, 00], [12, 30], ['SWA'], 'tutorial']
        ]
] as $k => $courses) {
    foreach ($courses as $result) {
        $timetable->add_record($k, new \local_cool\timetable\record($result[0], $result[1], $result[2], $result[3]));
    }
}

echo '<html>';
echo '<style>' . $timetable->generate_css() . '</style>';
echo '<style>
body{

font-family: "TechnikaLight","Technika","Open Sans","Helvetica Neue",Arial,sans-serif;
}
@font-face {
  font-family: \'TechnikaLight\';
  src: url(../../theme/ctufeet/fonts/Technika-Light.eot); /* IE9 Compat Modes */
  src: url(../../theme/ctufeet/fonts/Technika-Light.eot#iefix) format(\'embedded-opentype\'), /* IE6-IE8 */
  url(../../theme/ctufeet/fonts/Technika-Light.woff); format(\'woff\'), /* Modern Browsers */
  url(../../theme/ctufeet/fonts/Technika-Light.ttf); format(\'truetype\'), /* Safari, Android, iOS */
  url(../../theme/ctufeet/fonts/Technika-Light.svg#Technika-Light); format(\'svg\'); /* Legacy iOS */
  font-style: normal;
  font-weight: normal;
  text-rendering: optimizeLegibility;
}

</style>';
echo $timetable->generate_html();
echo '</html>';