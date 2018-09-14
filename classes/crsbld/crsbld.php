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

use backup;
use Exception;
use format_wiki\wiki_url;
use local_cool\entity\course;
use restore_controller;

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/course/modlib.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');

class crsbld {
    public $courseid;

    public static function create_new_course() : crsbld {
        return new crsbld();
    }

    public static function from_course(course $courseid) : crsbld {
        return new crsbld($courseid);
    }

    public function __construct(?course $courseid = null) {
        if ($courseid !== null) {
            $this->courseid = $courseid;
            wiki_url::set_current_context($courseid->get_context());
        } else {
            throw new Exception();
        }
    }

    public function clear_course() : crsbld {
        global $DB;
        $mods = get_course_mods($this->courseid->get_id());
        rebuild_course_cache($this->courseid->get_id(), false);
        foreach ($mods as $mod) {
            try {
                course_delete_module($mod->id);
            } catch (\moodle_exception $e) {
            }
        }
        $i = get_fast_modinfo($this->courseid->get_id());
        $infos = $i->get_section_info_all();
        foreach ($infos as $info) {
            try {
                if (!course_delete_section($this->courseid->get_id(), $info)) {
                    $DB->delete_records('course_sections', ['id' => $info->id]);
                }
            } catch (\moodle_exception $e){$DB->delete_records('course_sections', ['id' => $info->id]);}
        }
        foreach ($DB->get_records('course_sections', ['course' => $this->courseid->get_id()]) as $rec) {
            if ($rec->section > 0) {
                $DB->delete_records('course_sections', ['id' => $rec->id]);
            }
        }
        return $this;
    }

    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }

    private $section_cnt = 0;

    public function create_section(string $section_name, ?string $section_text = '',$visible=1) : crsbld_section {
        global $DB;
        if ($this->section_cnt > 0) {
            $section = course_create_section($this->courseid->get_id(), $this->section_cnt);
        } else {
            $section = $DB->get_record('course_sections', ['section' => 0, 'course' => $this->courseid->get_id()]);
            if ($section === false) {
                $section = course_create_section($this->courseid->get_id(), $this->section_cnt);
            }
            $section->section = 0;
        }
        $section->name = $section_name;
        $section->visible=$visible;
        $section->summary = $section_text;
        $section->summaryformat = 1;
        $DB->update_record('course_sections', $section);
        $this->section_cnt++;
        rebuild_course_cache($this->courseid->get_id(), false);
        return new crsbld_section($this, $section,$visible);
    }

}