<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 10.03.2018
 * Time: 15:57
 */

namespace formula_x\helper;


use formula_x\auto_mapper;
use formula_x\driver;
use formula_x\formula;
use formula_x\formula_error;
use formula_x\formula_function;
use formula_x\formula_operation_infix;
use formula_x\formula_operation_postfix;
use formula_x\formula_operation_prefix;
use formula_x\formula_operator;
use formula_x\parser\token;
use formula_x\stack;

class ReorderHelper
{
    /**
     * @param token[] $tokens
    */
    public static function postfix_to_formula(array $tokens) : formula
    {
        $mapping=auto_mapper::map_all();
        $operators=new stack();
        $closed_fns=[];
        foreach($tokens as $token)
        {
            switch ($token->getTokenType())
            {
                case token::TOKEN_TYPE_OPERAND:
                    $operators->push(formula_operator::from_token($token));
                    break;
                case token::TOKEN_TYPE_OPERATOR_PREFIX:
                    $var=$operators->pop();
                    $new=formula_operation_prefix::from_token($token);
                    $new->add_parameter($var);
                    $operators->push($new);
                break;
                case $token::TOKEN_TYPE_OPERATOR_POSTFIX:
                    $var=$operators->pop();
                    $new=formula_operation_postfix::from_token($token);
                    $new->add_parameter($var);
                    $operators->push($new);
                    break;
                case token::TOKEN_TYPE_OPERATOR_INFIX:
                    $var2=$operators->pop();
                    $var1=$operators->pop();
                    $new=formula_operation_infix::from_token($token);
                    $new->add_parameter($var1);
                    $new->add_parameter($var2);
                    $operators->push($new);
                    break;
                case token::TOKEN_TYPE_FUNCTION:
                    if($token->getTokenEvent()==token::TOKEN_EVENT_START) {
                        $found=false;
                        foreach ($mapping as $name=>$group)
                            if(in_array(strtoupper($token->getValue()),$group)) {
                                $fnc_name = 'formula_x\\functions\\'.strtolower($name).'\\function_' . strtolower($token->getValue());
                                $fnc=new $fnc_name();
                                $operators->push($fnc);
                                $found=true;
                                break;
                            }
                            if(!$found)
                                throw new formula_error(driver::get_string('name'));
                    }
                    else
                    {
                        $variables=new stack();
                        while(!$operators->peek()->is_function() || in_array($operators->peek(),$closed_fns))
                            $variables->push($operators->pop());
                        $fnc=$operators->pop();
                        /** @var formula_function $fnc*/
                        while(!$variables->empty())
                            $fnc->add_parameter($variables->pop());
                        $operators->push($fnc);
                        $closed_fns[]=$fnc;
                    }
                    break;

            }
        }
        return $operators->pop();
    }
    /**
     * @param token[] $tokens
    */
    public static function excel_to_postfix(array $tokens)
    {
        $output=[];
        $oper=new stack();
        foreach($tokens as $token)
        {
            switch ($token->getTokenType())
            {
                case token::TOKEN_TYPE_OPERAND:
                    $output[]=$token;
                    break;
                case token::TOKEN_TYPE_SUBEXPRESSION:
                    if($token->getTokenEvent()==token::TOKEN_EVENT_START) {
                        $output[]=$token;
                        $oper->push($token);
                    }
                    else
                    {
                        while($oper->peek()->getTokenEvent()!=token::TOKEN_EVENT_START && $oper->peek()->getTokenType()!=token::TOKEN_TYPE_SUBEXPRESSION)
                            $output[]=$oper->pop();
                        $output[]=$token;
                        $oper->pop();
                    }
                    break;
                case token::TOKEN_TYPE_FUNCTION:
                    if($token->getTokenEvent()==token::TOKEN_EVENT_START) {
                        $output[]=$token;
                        $oper->push($token);
                    }
                    else
                    {
                        while($oper->peek()->getTokenEvent()!=token::TOKEN_EVENT_START && $oper->peek()->getTokenType()!=token::TOKEN_TYPE_FUNCTION)
                            $output[]=$oper->pop();
                        $output[]=$token;
                        $oper->pop();
                    }
                    break;
                case token::TOKEN_TYPE_ARGUMENT:
                    while($oper->peek()->getTokenEvent()!=token::TOKEN_EVENT_END && $oper->peek()->getTokenType()!=token::TOKEN_TYPE_FUNCTION)
                        $output[]=$oper->pop();
                    $output[]=$token;
                    break;
                case token::TOKEN_TYPE_OPERATOR_POSTFIX:
                    $output[]=$token;
                    break;
                case token::TOKEN_TYPE_OPERATOR_INFIX:
                case token::TOKEN_TYPE_OPERATOR_PREFIX:
                    do {
                        if($oper->empty())
                            $comp=1;
                        else
                            $comp = self::compare($oper->peek(), $token);
                        if ($comp > 0)
                            $oper->push($token);
                        elseif ($comp<0)
                            $output[]=$oper->pop();
                        else
                        {
                            $output[]=$oper->pop();
                            $oper->push($token);
                        }
                        }
                        while($comp<0);
                break;
            }
        }
        while(!$oper->empty())
            $output[]=$oper->pop();
        return $output;
    }
    private static function compare(token $a, token $b) : int
    {
        if($a->getTokenEvent()==token::TOKEN_EVENT_START)
            return 1;
        $aa=self::number($a);
        $bb=self::number($b);
        if($aa==$bb)
            return 0;
        else if($aa<$bb)
            return 1;
        return -1;
    }
    private static function number(token $a) : int
    {
        $event=$a->getTokenEvent();
        $type=$a->getTokenType();
        switch ($type)
        {
            case token::TOKEN_TYPE_OPERATOR_PREFIX:
                return 90;
        }
        switch ($event)
        {
            case token::TOKEN_EVENT_RANGE:
            case token::TOKEN_EVENT_UNION:
            case token::TOKEN_EVENT_INTERSECTION:
                return 100;
            case token::TOKEN_EVENT_MATH:
                $base=70;
                switch ($a->getValue())
                {
                    case '^': return $base;
                    case '*':
                    case '/': return $base - 1;
                    case '+':
                    case '-': return $base - 2;
                }
            case token::TOKEN_EVENT_CONCATENATION:
                return 60;
            case token::TOKEN_EVENT_LOGICAL:
                return 50;

        }
        throw new \Exception();

    }
}