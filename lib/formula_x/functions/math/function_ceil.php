<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 08.03.2018
 * Time: 22:42
 */
namespace formula_x\functions\math;

use formula_x\formula_describer;
use formula_x\formula_error;
use formula_x\formula_function;
use formula_x\formula_operator;

class function_ceil extends formula_function
{
    function execute(): formula_operator
    {
        return formula_operator::from(ceil($this->execute_param(0)->val_as_number(false)));
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return 1;
    }
}