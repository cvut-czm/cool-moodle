<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 03/06/2018
 * Time: 14:13
 */

namespace formula_x\functions\ctu_electro;


use formula_x\formula_error;
use formula_x\formula_function;
use formula_x\formula_operator;

class function_ohmlaw extends formula_function
{

    function execute(): formula_operator
    {
        $voltage=$this->execute_param(0)->val_as_number(true);
        $amperage=$this->execute_param(1)->val_as_number(true);
        $resistance=count($this->parameters())==3?$this->execute_param(2)->val_as_number(true):null;
        $c=0;
        if($voltage==null && $amperage!=null && $resistance!=null)
            return formula_operator::from($amperage*$resistance);
        if($voltage!=null && $amperage==null && $resistance!=null)
            return formula_operator::from($voltage/$resistance);
        if($voltage!=null && $amperage!=null && $resistance==null)
            return formula_operator::from($voltage/$amperage);
        throw formula_error::create(formula_error::ERROR_VALUE);
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return [2,3];
    }
}