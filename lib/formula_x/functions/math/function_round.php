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

class function_round extends formula_function
{

    function execute(): formula_operator
    {
        return formula_operator::from(round($this->execute_param(0)->val_as_number(false),
                count($this->parameters())>1?
                        $this->execute_param(1)->val_as_integer():0
        ));
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return [1,2];
    }
}