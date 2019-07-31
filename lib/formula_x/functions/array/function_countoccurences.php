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

class function_countoccurences extends formula_function
{

    function execute(): formula_operator
    {
        $cnt=0;
        $val=$this->execute_param(0)->val();
        foreach ($this->parameters() as $parameter)
        {
            $res=$parameter->execute();
            if($res->val()!=$val)
                continue;
            $cnt++;
        }
        return formula_operator::from($cnt);
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return [2,255];
    }
}