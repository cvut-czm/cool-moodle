<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 29/05/2018
 * Time: 12:49
 */

namespace formula_x\functions\logical;


use formula_x\formula_function;
use formula_x\formula_operator;

class function_true extends formula_function
{

    function execute(): formula_operator
    {
        return formula_operator::from(true);
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        // TODO: Implement parameter_count() method.
    }
}