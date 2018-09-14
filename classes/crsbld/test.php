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

use format_wiki\wiki_url;
use local_cool\entity\course;
use local_cool\multilang;
use local_kos\entity\course_instance;
use tool_monitor\output\managesubs\subs;

defined('MOODLE_INTERNAL') || die();

class test {

    private const IMPORT_FOLDER = '/var/www/sites/moodle-prod/www/export/'; //'C:\Users\frycj\Downloads\\';

    public static function run(course $mdl_course) {
        #region Initialization for convert
        wiki_url::set_current_context($mdl_course->get_context()); // Setting current moodle context for course
        $lang = multilang::for_plugin('local_kos');  // Helps with creating multi-language strings
        $kos_instance =
                course_instance::get(['course_id' => $mdl_course->get_id()]);  // Course instance in semester (BI-BAP in B181)
        $kos_course = $kos_instance->get_kos_main_group()->get_course();  // Main KOS Course (BI-BAP)
        $l = $kos_course->code[2] !== 'E';
        $k = $kos_course->code[2] === 'K';
        $fixer = new link_fixer($mdl_course->get_context()); // Fix links

        $edux = edux_importer::from_tgz(self::IMPORT_FOLDER, $kos_course->code, $mdl_course->get_context(), $fixer)
                ->import($k?2:($l?0:1))// Import tgz files from edux exports.
                ->cleanup()// Remove empty pages, etc. Junk from EDUX
                ->generate_page_list();  // We keep page list as reference, so pages are not converted multiple times.

        $annotation = $edux->has_page('/annotation/start') ? $edux->get_page('/annotation/start')->content : null;

        $crsbld = crsbld::from_course($mdl_course)// Reference to moodle course
        ->clear_course()// Remove moodle course content
        ->create_section($l ? 'Anotace kurzu' : 'Course annotation', $annotation)// Section 0
        ->back_to_crsbld()
                ->create_section($l ? 'Hodnocení' : 'Evaluation')
                ->add_page($l ? 'Podmínky absolvování předmětu' : 'Course requirements',
                        $edux->get_page('/classification/start')->content)// Adding classification page
                ->back_to_crsbld();
        #endregion

        foreach (['/lectures', '/lectures/', '/tutorials', '/tutorials/', '/labs', '/labs/', '/teacher', '/teacher/',] as $p) {
            $fixer->add_resource($p, '/course/view.php?id=' . $mdl_course->get_id());
        }
        #region Create section overviews
        $overviews = ['lectures' => null, 'tutorials' => null, 'labs' => null, 'teacher' => null];
        foreach ($overviews as $k => $v) {
            if (!$edux->has_subpage("/$k")) {
                continue;
            }
            if ($edux->has_page("/$k/start")) {
                $overviews[$k] = $edux->get_page("/$k/start"); // Will use section start page
            } else {
                $overviews[$k] = new edux_crs();// No section start page found
            }
        }
        #endregion

        #region Private section for teachers
        $mapping = [
                '/^.*\/solutions\/?.*?$/',
                '/^\/private\/?.*?$/'
        ];
        $private_pages = [];
        foreach ($mapping as $map) {
            foreach ($edux->get_regex_pages($map) as $page) {
                $private_pages[] = $page;
            }
        }
        #endregion

        $ll = [
                'lectures' => $l ? 'Přednášky' : 'Lectures',
                'tutorials' => $l ? 'Cvičení' : 'Tutorials',
                'labs' => $l ? 'Laboratoře' : 'Labs',
                'teacher' => $l ? 'Učitelé' : 'Teachers'
        ];
        #region Generate main sections
        foreach ($overviews as $k => $v) {
            if ($v === null) {
                continue;
            }

            $section = $crsbld->create_section($ll[$k], $v->content);
            $fixer->add_redone('course_sections', $section->section->id, ['summary']);
            foreach ($edux->get_regex_pages('/^\/'.$k.'\/?.*?$/') as $kk => $page) {
                if($page->stored_file->get_filename()=='start.txt')
                    $k=$page->stored_file->get_filepath();
                else
                    $k=$page->stored_file->get_filepath().substr($page->stored_file->get_filename(),0,-4);
                $section->add_page($page->title, $page->content, $fixer, $k);
            }
        }
        #endregion

        $private = $crsbld->create_section($l ? 'Privátní' : 'Private', '', 0);
        foreach ($private_pages as $k => $page) {
            $private->add_page($page->title, $page->content, $fixer, $k);
        }

        #region Generate other pages that we cannot map to anything else
        $others = $crsbld->create_section($l ? 'Ostatní' : 'Others','', 0);
        foreach ($edux->get_pages('/') as $k => $page) {
            if (strlen($page->content) > 10) {
                if($page->stored_file->get_filename()=='start.txt')
                    $k=$page->stored_file->get_filepath();
                else
                    $k=$page->stored_file->get_filepath().substr($page->stored_file->get_filename(),0,-4);
                $others->add_page($page->title, $page->content, $fixer, $k);
            }
        }
        #endregion
        $fixer->redone();
    }
}