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

class timetable {

    private $options;
    /** @var day[] $days */
    private $days;

    public function __construct(timetable_options $options) {
        $this->options = $options;
        foreach ($options->days as $v) {
            $this->days[$v] = new day();
        }
    }

    public function add_record(int $day, record $record) {
        $inc = $this->options->lowest_increment;
        $record->start_time=[$record->start_time[0]-$this->options->time_start[0],
                $record->start_time[1]-$this->options->time_start[1]];
        $record->end_time=[$record->end_time[0]-$this->options->time_start[0],
                $record->end_time[1]-$this->options->time_start[1]];
        $record->start_time = ($record->start_time[0] * 60 + $record->start_time[1]) / $inc;
        $record->end_time = ($record->end_time[0] * 60 + $record->end_time[1]) / $inc;
        $this->days[$day]->add_record($record);
    }
    public function translate($in)
    {
        $trans=['day0'=>'Po','day1'=>'Ut','day2'=>'St','day3'=>'Ct','day4'=>'Pa'];
        return $trans[$in];
    }

    private function generate_header()
    {
        $output='<div class="header"><div class="empty"></div>';
        $starttime=$this->options->time_start[1]>0?$this->options->time_start[0]-1:$this->options->time_start[0];
        $endtime=$this->options->time_end[1]>0?$this->options->time_end[0]+1:$this->options->time_end[0];
        $f=(int)(60/$this->options->lowest_increment);
        for($i=$starttime;$i<=$endtime;$i++)
        {
            $output.='<div class="flex-g-'.$i.'">'.$i.':00</div>';
        }
        return $output.'</div>';
    }

    public function generate_html() {
        $lines = [];
        $output = '<div class="timetable">'.$this->generate_header();
        $i=0;
        foreach ($this->days as $day) {
            $output .= '<div class="day"><div class="day_name">'.$this->translate('day'.$this->options->days[$i]).'</div><div class="records">';
            $i++;
            $line = $day->line_records($this->options);
            $last=0;
            foreach ($line as $l) {
                $output .= $l->print($this->options);
                $last=$l->last_end();
            }
            $last=$this->options->elements()-$last;
            if($last>0)
                $output.='<div class="flex-g-'.$last.'"></div>';
            $output .= '</div></div>';
        }
        echo $output . '</div>';
    }

    public function generate_css() {
        $css = new css_provider($this->options);
        return $css->generate();
    }
}