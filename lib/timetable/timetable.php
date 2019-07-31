<?php

namespace timetable;

class timetable {
    use helper;
    public $days = [];

    protected function render_grid() : string {
        $out = '<div class="grid" data-content="'.$this->o->grid_title.'">';
        for ($i = $this->o->start / 60 - $this->o->start % 60; $i < $this->o->end / 60 - $this->o->end % 60; $i+=1+$this->o->skip_grid)
            $out .= '<div data-content="' . ($this->o->show_grid_hours ? $i : '') . '"></div>';
        return $out . '</div>';
    }

    public function render() : string {
        return $this->partial('timetable', ['%class%' => $this->o->show_day_header ? '' : 'headless',
                '%grid%' => $this->o->show_grid ? $this->render_grid() : '', '%days%' => $this->partials($this->days)]);
    }
}
class day {
    use helper;
    public $records = [];

    public function render() : string {
        uasort($this->records, function(record $a, record $b) {
            return $a->compare($b);
        });
        $lines = [null, null, null, null];
        foreach ($this->records as $record)
            for ($line = 0; $line < 4; $line++)
                if ($lines[$line] == null || $record->compare($lines[$line], 'start', 'end') !== -1) {
                    $lines[$line] = $record;
                    $count = 0;
                    for ($f = $line + 1; $f < 4; $f++)
                        $count += $lines[$f] != null && $record->compare($lines[$f], 'start', 'end') == -1 ? 1 : 0;
                    $span = 12 / ($line + $count + 1);
                    for ($e = 0; $e < $line + $count + 1; $e++)
                        $lines[$e]->row = [$e * $span + 1, $span];
                    break;
                }
        $title = $this->o->show_day_header ? ($this->o->show_day_abbrev ? $this->title . '_abbrev' : $this->title) : 'empty';
        uasort($this->records, function(record $a, record $b) {
            return $a->row[0] == $b->row[0] ? -$a->compare($b) : ($a->row[0] < $b->row[0] ? -1 : 1);});
        return $this->partial('day',
                ['%vh%' => $this->o->day_height, '%header%' => ($this->o->render_head)($title,$this),
                        '%records%' => $this->partials($this->records),'cols'=>$this->render_cols()]);
    }
}
trait helper {
    protected $o;
    private $lang_data = null;
    private $title = null;
    private static $template = ['timetable' => '<div class="timetable %class%">%grid%%days%</div>',
            'day' => '<div class="day" style="height: %vh%;"><div class="day-header">%header%</div><div class="day-content" style="grid-template-columns: repeat(%cols%, 1fr);">%records%</div></div>',
            'record' => '<div class="record %class%" %grid%><input %disabled% id="record_in_%id%" class="record-tg" type="checkbox"/><label for="record_in_%id%" class="record-header">%label%<div></div></label><div class="record-content"><div>%content%</div></div></div>'];

    public function __construct(options $options, string $title = null) {
        $this->o = $options;
        if (property_exists(static::class, 'days'))
            foreach ($options->days as $day)
                $this->days[] = new day($options, $day);
        $this->title = $title;
    }

    protected function render_cols() : int {
        return ($this->o->end - $this->o->start) / $this->o->precision;
    }

    public function ln(string $id) : string {
        if ($this->lang_data == null) {
            $this->lang_data = parse_ini_file(__DIR__ . '/timetable_' . $this->o->lang . '.ini');
        }
        return $this->lang_data[$id];
    }

    protected function partial(string $template, array $data = []) : string {
        return strtr(self::$template[$template], $data);
    }

    protected function partials(array $partials) : string {
        $out = '';
        foreach ($partials as $partial)
            $out .= $partial->render();
        return $out;
    }
}
class options {
    public $lang = 'cs';
    public $disabled = false, $skip_grid=0, $grid_title='';
    public $render_head;
    public $precision = 15, $start = 7 * 60, $end = 21 * 60;
    public $show_day_header = true, $show_day_abbrev = true, $show_grid = true, $show_grid_hours = true;
    public $day_height = '80px', $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'], $selected_day = -1;

    public function __construct() {
        $this->render_head=function($title,$helper){return $helper->ln($title);};
    }
}
class record {
    use helper;
    private static $id = 0;
    public $start = 0, $end = 0, $class = '', $content = '', $label = '', $row = [1, 12];

    public function compare(record $b, string $pA = 'start', string $pB = 'start') : int {
        return ($this->{$pA} < $b->{$pB}) ? -1 : (($this->{$pA} > $b->{$pB}) ? 1 : 0);
    }

    public function render() : string {
        $id = self::$id++;
        $col = [($this->start - $this->o->start) / $this->o->precision + 1, ($this->end - $this->start) / $this->o->precision];
        $pos = "style='grid-area:{$this->row[0]} / {$col[0]} / span {$this->row[1]} / span {$col[1]}'";
        return $this->partial('record', ['%id%' => $id, '%grid%' => $pos, '%disabled%' => $this->o->disabled ? 'disabled' : '',
                '%class%' => $this->class, '%label%' => $this->label, '%content%' => $this->content]);
    }
}