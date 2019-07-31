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

class function_avg extends formula_function
{

    function execute(): formula_operator
    {
        $sum=0;
        $cnt=0;
        foreach ($this->parameters() as $parameter) {
            $val = $parameter->execute();
            if($val->is_null())
                continue;
            $val=$val->val_as_number(false);
            $sum+=$val;
            $cnt++;
        }
        return formula_operator::from($cnt==0?0:$sum/$cnt);
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return [1,255];
    }
}