<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 03/06/2018
 * Time: 10:13
 */

namespace formula_x\functions\ctu_crypto;


use formula_x\formula_function;
use formula_x\formula_operator;

class function_count extends formula_function
{

    function execute(): formula_operator
    {
        return formula_operator::from(count($this->parameters()));
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return [1,255];
    }
}