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

namespace local_cool\crsbld;

use Markup\Edux\Title;

defined('MOODLE_INTERNAL') || die();

class edux_crs {

    /** @var \stored_file $stored_file */
    public $stored_file;
    public $content = '';
    public $title = 'Null';

    public static $renderer;

    public function render() : edux_crs {
        Title::$last_title = null;
        $this->content = self::$renderer->render($this->stored_file->get_content());
        $this->title = Title::$last_title === null ? substr($this->stored_file->get_filename(), 0, -4) : Title::$last_title;
        return $this;
    }
}