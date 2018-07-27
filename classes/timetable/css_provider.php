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

class css_provider {

    private $options;

    public function __construct(timetable_options $options) {
        $this->options = $options;
    }

    public function generate(): string {
        $output = '';
        $output .= $this->generate_records();
        $output .= $this->generate_flexbox();
        return $output;
    }

    private function generate_flexbox(): string {
        $builder = css_builder::get()->pre_selector('.timetable ');
        $st = $this->options->time_start;
        $st = $st[0] * 60 + $st[1];
        $en = $this->options->time_end;
        $en = $en[0] * 60 + $en[1];
        $inc = $this->options->lowest_increment;
        $elements = ($en - $st) / $inc;
        for ($i = 1; $i <= $elements; $i++) {
            $builder->selector('.flex-g-' . $i)
                    ->tag_start()
                    ->setting('flex-grow', $i)
                    ->tag_end()
                    ->empty_line();
        }
        $builder->selector('.day')
                ->tag_start()
                ->setting('display','flex')
                ->tag_end()->empty_line();
        $builder->selector('.day > .day_name')
                ->tag_start()
                ->setting('flex-basis','20px')
            ->tag_end()->empty_line();

        $builder->selector('.header')
                ->tag_start()
                ->setting('height', '40px')
                ->setting('display','flex')
                ->setting('flex-wrap','nowrap')
                ->setting('justify-content','space-around')
                ->setting('align-items','stretch')
                ->tag_end()->empty_line();
        $builder->selector('.header > .empty')
                ->tag_start()
                ->setting('flex-basis','20px')
                ->tag_end()->empty_line();
        $builder->selector('.day > .records')
                ->tag_start()
                ->setting('display', 'flex')
                ->setting('flex-grow', '1')
                ->tag_end();
        $builder->selector('.record')
                ->tag_start()
                ->setting('text-align','right')
                ->tag_end()->empty_line();
        return $builder->build();
    }
    function adjustBrightness($hex, $steps) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color   = hexdec($color); // Convert to decimal
            $color   = max(0,min(255,$color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }
    private function generate_records(): string {
        $builder = css_builder::get()->pre_selector('.timetable ');
        foreach ($this->options->record_types as $record_type) {
            $builder->selector('.record.' . $record_type->id)
                    ->tag_start()
                    ->setting('background-color', $record_type->bgcolor)
                    ->setting('border', '1px solid '.$this->adjustBrightness($record_type->bgcolor,-75))
                    ->tag_end()
                    ->empty_line();
        }
        return $builder->build();
    }
}