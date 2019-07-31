<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 08.03.2018
 * Time: 22:42
 */
namespace formula_x\functions\information;

use formula_x\formula_describer;
use formula_x\formula_error;
use formula_x\formula_function;
use formula_x\formula_operator;

class function_isodd extends formula_function
{
    function parameter_count()
    {
        return 1;
    }
    function self_describer() : formula_describer
    {
        return null;
    }
    public function execute(): formula_operator
    {
        $val=$this->parameters()[0]->execute()->val_as_integer();
        return formula_operator::from($val%2==1);
    }
}