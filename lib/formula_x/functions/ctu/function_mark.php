<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 03/06/2018
 * Time: 10:13
 */

namespace formula_x\functions\ctu;


use formula_x\formula_function;
use formula_x\formula_operator;

class function_mark extends formula_function
{

    function execute(): formula_operator
    {
        $n=$this->parameters()[0]->execute()->val_as_number();
        return formula_operator::from($n>90?'A':($n>80?'B':($n>70?'C':($n>60?'D':($n>50?'E':'F')))));
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return [1,2];
    }
}