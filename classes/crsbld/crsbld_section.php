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

use PHPUnit\Runner\Exception;

defined('MOODLE_INTERNAL') || die();

class crsbld_section {

    private $crsbld;
    public $section;
    private $visible;
    public function __construct(crsbld $instance,$section,$visible) {
        $this->section=$section;
        $this->crsbld=$instance;
        $this->visible=$visible;
    }

    public function add_book(string $name,string $description,bool $show_description=true) : crsbld_book
    {
        return new crsbld_book();
    }
    public function add_page(string $name,?string $content,link_fixer $fixer=null,$k=null) : crsbld_section{
        global $DB;
        if($content!=null && strlen($content)>0) {
            $data = new \stdClass();
            $data->content = $content;
            $data->contentformat = 1;
            $data->section = $this->section->section;
            $data->visible = $this->visible;
            $data->module = 18;
            $data->modulename = 'page';
            $config = get_config('page');
            $data->display = $config->display;
            $data->popupheight = $config->popupheight;
            $data->popupwidth = $config->popupwidth;
            $data->printheading = $config->printheading;
            $data->printintro = $config->printintro;
            $data->groupingid = 0;
            $data->name = $name;
            try {

                $o=add_moduleinfo($data, get_course($this->crsbld->courseid->get_id()));
                if($fixer!=null) {
                    $fixer->add_resource($k, '/mod/page/view.php?id=' . $DB->get_field('course_modules','id',['instance'=>$o->instance]));
                    $fixer->add_redone('page', $o->instance, ['content']);
                }
            }catch( \Exception $e)
            {
            }
        }
        return $this;
    }

    public function add_kos_annotation() : crsbld_section {
        return $this;
    }

    public function back_to_crsbld() : crsbld
    {
        return $this->crsbld;
    }
}