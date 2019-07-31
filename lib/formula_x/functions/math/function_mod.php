<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 03/06/2018
 * Time: 11:10
 */

namespace formula_x\functions\math;


use formula_x\formula_function;
use formula_x\formula_operator;

class function_mod extends formula_function
{

    function execute(): formula_operator
    {
        $number=$this->execute_param(0)->val_as_integer();
        $modulo=$this->execute_param(1)->val_as_integer();
        return formula_operator::from($number % $modulo);
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return 2;
    }
}