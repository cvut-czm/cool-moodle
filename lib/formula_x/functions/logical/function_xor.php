<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 03/06/2018
 * Time: 12:09
 */

namespace formula_x\functions\logical;


use formula_x\formula_function;
use formula_x\formula_operator;

class function_xor extends formula_function
{

    function execute(): formula_operator
    {
        $set=false;
        foreach ($this->parameters() as $parameter)
            if($parameter->execute()->val_as_logical()==true)
                if($set)
                    return formula_operator::from(false);
                else
                    $set=true;
        return formula_operator::from($set);
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return [2,PHP_INT_MAX];
    }
}