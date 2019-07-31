<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 10.03.2018
 * Time: 10:26
 */

namespace formula_x;


use formula_x\parser\token;

class formula_operation_prefix extends formula_operation
{
    public static function from_token(token $token)
    {
        $operation=new formula_operation_prefix();
        $operation->set_operator($token->getValue());
        return $operation;
    }

    function add_parameter($parameter)
    {
        if(count($this->params)==0)
            $this->params[]=$parameter;
        else
            $this->params[0]->add_parameter($parameter);
    }

    function execute(): formula_operator
    {
        switch ($this->operator)
        {
            case '-':
                $val=$this->parameters()[0]->execute()->val();
                if(!is_numeric($val)&&!is_bool($val))
                    throw new formula_error(driver::get_string('value'));
                return new formula_operator(-$val);
        }
        return $this->parameters()[0]->execute()->val();
    }

}