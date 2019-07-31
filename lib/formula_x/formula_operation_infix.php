<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 10.03.2018
 * Time: 10:26
 */

namespace formula_x;


use formula_x\parser\token;

class formula_operation_infix extends formula_operation
{
    public static function from_token(token $token)
    {
        $operation=new formula_operation_infix();
        $operation->set_operator($token->getValue());
        return $operation;
    }

    function add_parameter($parameter)
    {
        if(count($this->params)<=1)
            $this->params[]=$parameter;
        else
            $this->params[1]->add_parameter($parameter);
    }

    function execute(): formula_operator
    {
        $val1=$this->parameters()[0]->execute()->val();
        $val2=$this->parameters()[1]->execute()->val();
        switch ($this->operator) {
            case '&':
                return new formula_operator($val1.''.$val2);
            case '=':
                return new formula_operator($val1=$val2);
            case '<>':
                return new formula_operator($val1!=$val2);
            case '<':
                return new formula_operator($val1<$val2);
            case '>':
                return new formula_operator($val1>$val2);
            case '<=':
                return new formula_operator($val1<=$val2);
            case '>=':
                return new formula_operator($val1>=$val2);
            default:break;
        }
        if(!is_numeric($val1)&&!is_bool($val1))
            throw new formula_error(driver::get_string('value'));
        if(!is_numeric($val2)&&!is_bool($val2))
            throw new formula_error(driver::get_string('value'));
        switch ($this->operator)
        {
            case '-':
                return new formula_operator($val1-$val2);
            case '+':
                return new formula_operator($val1+$val2);
            case '*':
                return new formula_operator($val1*$val2);
            case '/':
                return new formula_operator($val1/$val2);
            case '^':
                return new formula_operator(pow($val1,$val2));
        }
        throw new formula_error('???');
    }

}