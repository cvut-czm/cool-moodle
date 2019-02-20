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
use local_kos\entity\semester;
use Markup\Edux\LinkProcessor;

defined('MOODLE_INTERNAL') || die();

class edux_importer {
    /** @var link_fixer $fixer */
    private $fixer;
    private $folder;
    private $code;
    private $context;
    /** @var edux_crs[] $pages */
    private $pages = [];
    /** @var \WikiRenderer\Renderer $renderer */
    private $renderer;

    public static function from_tgz(string $folder, string $code, \context_course $context, link_fixer $fixer) : edux_importer {
        $object = new edux_importer();
        $object->fixer = $fixer;
        $object->folder = $folder;
        $object->code = $code;
        $object->context = $context;

        $markupConfig = new  \Markup\Edux\Config($context, $fixer);
        $generator = new \Generator\MoodleHtml\Document(new \Generator\MoodleHtml\Config());
        $object->renderer = new \WikiRenderer\Renderer($generator, $markupConfig);
        edux_crs::$renderer=$object->renderer;
        return $object;
    }

    public function assign_fixer(link_fixer $fixer) : edux_importer {
        return $this;
    }

    public function generate_page_list() : edux_importer {
        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'format_wiki', 'pages', 0, 'itemid, filepath, filename', false);
        foreach ($files as $file) {
            $o = new edux_crs();
            $o->stored_file = $file;
            LinkProcessor::set_section(substr($file->get_filepath(),0,-1));
            $o->render();
            preg_match_all('/\/teacher\/([^@]*@[^"]*)/', $o->content, $matches);
            for ($i=0;$i<count($matches[0]);$i++) {
                $o->content = str_replace($matches[0][$i], 'mailto:'.$matches[1][$i], $o->content);
            }
            $this->pages[$file->get_filepath() . $file->get_filename()] = $o;
        }
        return $this;
    }

    public function import(int $type=0) : edux_importer {
        $context2 = new \local_kos\kos_context();
        $data = \local_kos\api\kosapi\entities\course::fetchCourse($this->code, $context2, 'B182');

        if(file_exists($this->folder.$this->code.'_pages.tgz')) {
            $pages_file = $this->folder . $this->code . '_pages.tgz';
            $media_file = $this->folder . $this->code . '_media.tgz';
            $type=0;
        }
        else {
            $pages_file = $this->folder . substr($this->code, 0, 2) . substr($this->code, 3) . '_pages.tgz';
            $media_file = $this->folder . substr($this->code, 0, 2) . substr($this->code, 3) . '_media.tgz';
        }
        if (file_exists($media_file) && filesize($media_file) > 636870912) {
            throw new \Exception();
        }

        $test = \local_kos\entity\course::get($this->code, 'code');
        if (count($test->get_instances()) > 0) {
            echo 'Already loaded ' . $this->code . PHP_EOL;
            $crs = $test->get_instance(semester::get(['code'=>'B182']));
        } else {
            $crs = \local_kos\course_builder::create()
                    ->set_semester(semester::get(['code'=>'B182']))
                    ->add_kos_courses([$this->code])
                    ->set_main_course($this->code)
                    ->create_empty()
                    ->set_use_wiki(true)
                    ->build();
        }
        $context = context_course::instance($crs->course_id);
        $porter = new \format_wiki\porter();
        $porter->set_context($context);
        $porter->set_pages($pages_file);
        if (file_exists($media_file)) {
            $porter->set_media($media_file);
        }
        $porter->delete_old();
        $porter->port(true,$type);
        echo 'Course ' . $this->code . ' is ported.' . PHP_EOL;
        return $this;
    }

    public function cleanup() : edux_importer {
        edux_cleanup::run($this->context);
        return $this;
    }

    public function has_page(string $page) : bool {
        $page.='.txt';
        return isset($this->pages[$page]);
    }
    public function has_subpage(string $page) : bool {
        foreach ($this->pages as $k=>$p)
            if(strpos($k,$page)===0)
                return true;
        return false;
    }

    /** @return edux_crs */
    public function create_toc(string $folder) {
        $out=new edux_crs();
        $render='<table><tr><th>#</th><th>Content</th></tr>';
        for($i=0;$i<13;$i++)
        {
            $e=$i;
            if($e<10)
                $e='0'.$e;
            $url=$folder.$e.'/start.txt';
            if(!isset($this->pages[$url]))
                continue;
            $render.='<tr><td>Week '.$e.'</td><td></td></tr>';
        }
        $out->content=$render.'</table>';
        $out->title='TOC';
        return $out;
    }

    /** @return edux_crs */
    public function get_page(string $page) : ?edux_crs {
        $page.='.txt';
        if (isset($this->pages[$page])) {
            $out=$this->pages[$page];
            unset($this->pages[$page]);
            return $out;
        }
        return null;
    }

    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
    /** @return edux_crs[] */
    public function get_regex_pages(string $regex) {
        $out=[];
        foreach ($this->pages as $key=>$page)
        {
            if(!preg_match($regex,$key))
                continue;
            if(!$this->endsWith($key,'.txt'))
                continue;
            $out[]=$page;
            unset($this->pages[$key]);
        }
        return $out;
    }

    /** @return edux_crs[] */
    public function get_pages(string $folder, bool $except_start = false) {
        $out=[];
        foreach ($this->pages as $key=>$page)
        {
            if(strpos($key,$folder)!==0)
                continue;
            if(!$this->endsWith($key,'.txt'))
                continue;
            $out[]=$page;
            unset($this->pages[$key]);
        }
        return $out;
    }
}