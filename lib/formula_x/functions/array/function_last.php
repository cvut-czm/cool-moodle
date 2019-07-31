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

class function_last extends formula_function
{

    function execute(): formula_operator
    {
        $last=formula_operator::null();
        foreach ($this->parameters() as $parameter)
        {
            $res=$parameter->execute();
            if($res->is_null())
                continue;
            $last=$res;
        }
        return $last;
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return [1,255];
    }
}