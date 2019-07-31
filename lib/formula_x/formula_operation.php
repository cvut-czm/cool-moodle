<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 10.03.2018
 * Time: 12:50
 */

namespace formula_x;


abstract class formula_operation implements formula
{

    protected $params=[];
    protected $operator;
    /**
     * @return formula[]
     */
    function parameters(): array
    {
        return $this->params;
    }

    function set_operator($operator)
    {
        $this->operator=$operator;
    }

    function is_function(): bool
    {
        return false;
    }

    function is_operator(): bool
    {
        return false;
    }

    function is_operation(): bool
    {
        return true;
    }
    function as_operator(): formula_operator
    {
        return null;
    }
}