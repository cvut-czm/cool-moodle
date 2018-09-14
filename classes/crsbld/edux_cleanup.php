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

use context_course;
use stored_file;

defined('MOODLE_INTERNAL') || die();

define('debug',false);
class edux_cleanup {
    private static function only_header_page(stored_file $file) : bool {
        $content = $file->get_content();
        if (preg_match('/\A{{[^(}})]*}}\n\n======[^(======)]*======\n*({{[^(}})]*}})?\n*\z/', $content)) {
            if (!debug) {
                $file->delete();
            }
            return true;
        }
        return false;
    }

    private static function remove_founding(stored_file $file) : bool {
        if (
                ($file->get_filepath() == '/' &&
                        ($file->get_filename() == 'funding.txt' || $file->get_filename() == 'sidebar.txt' || $file->get_filename() == 'schedule.txt')) ||
                ($file->get_filepath() == '/classification/' && $file->get_filename() != 'start.txt')
        ) {
            if (!debug) {
                $file->delete();
            }
            return true;
        }
        return false;
    }

    private static function remove_unused_pages(stored_file $file) : bool {
        $content = $file->get_content();
        if (strlen(trim($content)) == 0 ||
                preg_match('/\A({{[^(}})]*}})?\n*======[^(======)]*======\n*<note>[^<]*<\/note>\n*({{[^(}})]*}})?\n*\z/',
                        $content)) {
            if (!debug) {
                $file->delete();
            }
            return true;
        }
        return false;
    }

    private static function remove_unused_link_pages(stored_file $file) : bool {
        $content = $file->get_content();
        if (preg_match('/\A======[^(======)]*======\n\s*\*\s*\[\[[^(\]\]\)]*\]\]\n*\z/', $content)) {
            if (!debug) {
                $file->delete();
            }
            return true;
        }
        return false;
    }

    private static function remove_wrong_namespaces(stored_file $file) : bool {
        $wrong = [
                '/student',
                '/en/student',
                '/av',
                '/anketa',
                '/harmonogram/',
                '/harmonogram-test/',
                '/team',
                '/en/team',
                '/playground',
                '/wiki',
                '/classification/student',
                '/classification/view',
                '/en/classification/student',
                '/en/classification/view'
        ];
        foreach ($wrong as $w) {
            if (strpos($file->get_filepath(), $w) === 0) {
                if (!debug) {
                    $file->delete();
                }
                return true;
            }
        }
        return false;
    }

    private static function search($dir) {
        $c = 0;
        foreach ($dir['subdirs'] as $subdir) {
            $c += self::search($subdir);
        }
        if ((count($dir['files']) + count($dir['subdirs']) - $c) === 0) {
            if (!debug) {
                if ($dir['dirfile'] != null) {
                    $dir['dirfile']->delete();
                }
            }
            return 1;
        }
        return 0;
    }

    public static function run(context_course $context) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'format_wiki', 'pages', 0,
                "itemid, filepath, filename",
                false);
        foreach ($files as $file) {
            if (self::only_header_page($file) || self::remove_founding($file) || self::remove_wrong_namespaces($file) ||
                    self::remove_unused_pages($file) || self::remove_unused_link_pages($file)) {
                continue;
            }
        }
        $dirs = $fs->get_area_tree($context->id, 'format_wiki', 'pages', 0);

        self::search($dirs);
    }
}