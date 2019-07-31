<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 31/05/2018
 * Time: 13:41
 */

namespace formula_x\table;


class column_definition
{
    private $columns=[];
    public function add_column(string $name,int $width)
    {
        $this->columns[]=['name'=>$name,'width'=>$width];
    }
}