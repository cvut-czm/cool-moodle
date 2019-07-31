<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 31/05/2018
 * Time: 13:39
 */

namespace formula_x\table;

class table_builder {
    const TABLE_START = "<table %0>";
    const TABLE_END = "</table>";
    const TR_START = "<tr %0>";
    const TR_END = "</tr>";
    const TD_START = "<td %0>";
    const TD_END = "</td>";
    const TH_START = "<th %0>";
    const TH_END = "</th>";
    const THEAD_START = "<thead %0>";
    const THEAD_END = "</thead>";
    const TBODY_START = "<tbody %0>";
    const TBODY_END = "</tbody>";
    const INPUT = "<input type=\"text\" %0 />";

    private $attr_table = [
            'class' => 'formulax_table'
    ];
    private $col_def;
    private $row_def;
    private $options;
    private $width;
    private $height;
    private $content = [[1]];

    private function __construct(table_options $options) {
        $this->col_def = new column_definition();
        $this->options = $options;
    }

    public static function create(table_options $options) {
        return new table_builder($options);
    }

    public function column_definitions() : column_definition {
        return $this->col_def;
    }

    public function set_size(int $x, int $y) : table_builder {
        $this->width = $x;
        $this->height = $y;
        return $this;
    }

    private function attribute_builder(... $data) {
        $out = "";
        for ($i = 0; $i < count($data); $i += 2) {
            $attributes = "";
            if ($data[$i + 1] != null) {
                foreach ($data[$i + 1] as $k => $v) {
                    $attributes .= " {$k}=\"{$v}\"";
                }
            }
            $out .= str_replace("%0", $attributes, $data[$i]) . PHP_EOL;
        }
        return $out;
    }

    public function render() : string {
        $out = $this->attribute_builder(self::TABLE_START, $this->attr_table);
        $out .= $this->attribute_builder(self::TR_START, null);
        $out .= $this->attribute_builder(
                self::TH_START, null,
                self::TH_END, null
        );
        for ($x = 1; $x <= $this->width; $x++) {
            $out .= $this->attribute_builder(
                    self::TH_START, null,
                    chr($x+64),null,
                    self::TH_END, null
            );
        }
        $out .= $this->attribute_builder(self::TR_END, null);
        for ($y = 1; $y <= $this->height; $y++) {
            $out .= $this->attribute_builder(self::TR_START, null);
            $out .= $this->attribute_builder(
                    self::TD_START, null,
                    $y,null,
                    self::TD_END, null
            );
            for ($x = 1; $x <= $this->width; $x++) {
                if (isset($this->content[$x]) && isset($this->content[$x][$y])) {
                    $out .= $this->attribute_builder(
                            self::TD_START, $this->content[$x][$y]['attr'],
                            self::INPUT, ['name'=>'tab_'.$y.'_'.$y,'value' => $this->content[$x][$y]['value']],
                            self::TD_END, null
                    );
                } else {

                    $out .= $this->attribute_builder(
                            self::TD_START, null,
                            self::INPUT, ['name'=>'tab_'.$y.'_'.$y,'value' => ''],
                            self::TD_END, null
                    );
                }
            }
            $out .= $this->attribute_builder(self::TR_END, null);
        }
        $out .= self::TABLE_END;
        return $out;
    }
}