<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 29/05/2018
 * Time: 12:55
 */

namespace formula_x\functions\logical;


use formula_x\formula_function;
use formula_x\formula_operator;

class function_false extends formula_function
{

    function execute(): formula_operator
    {
        return formula_operator::from(false);
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        // TODO: Implement parameter_count() method.
    }
}