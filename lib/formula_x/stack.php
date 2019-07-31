<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 09.03.2018
 * Time: 5:37
 */

namespace formula_x;


class stack
{
    private $arr=[];
    private $pos=-1;

    public function peek()
    {
        return $this->arr[$this->size()-1];
    }
    public function push($value)
    {
        array_push($this->arr,$value);
    }
    public function size()
    {
        return count($this->arr);
    }
    public function empty()
    {
        return count($this->arr)==0;
    }
    public function pop()
    {
        return array_pop($this->arr);;
    }
}