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

class function_min extends formula_function
{

    function execute(): formula_operator
    {
        $min=null;
        foreach ($this->parameters() as $parameter) {
            $val = $parameter->execute();
            if($val->is_null())
                continue;
            $val=$val->val_as_number(false);
            if($min==null || $min>$val)
                $min=$val;
        }

        if($min==null)
            return formula_operator::null();
        return formula_operator::from($min);
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return [1,255];
    }
}