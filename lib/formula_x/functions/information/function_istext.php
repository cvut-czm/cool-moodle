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

class function_istext extends formula_function
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
        $test=$this->parameters()[0]->execute();
        if($test->is_null())
            return formula_operator::false();
        try {
            $val = $test->val_as_number();
            return formula_operator::false();
        }
        catch (formula_error $error) {
            return formula_operator::true();
        }
    }
}