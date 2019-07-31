<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 10.03.2018
 * Time: 10:28
 */

namespace formula_x;


use formula_x\parser\token;

class node
{
    private $nodes = [];

    /**
     * @param token[] $tokens
    */
    public function build($tokens,int $start=0,bool $end_imd=false)
    {
        $c=$start;
        $last=new stack();
        $stack=null;
        $last->push(null);
        for(;$c<count($tokens);$c++)
        {
            $token=$tokens[$c];
            switch ($token->getTokenType()) {
                case token::TOKEN_TYPE_OPERAND:
                    $t=formula_operator::from_token($token);
                    if($last->peek()==null)
                        $stack=$t;
                    else
                        $last->peek()->add_parameter($t);
                    break;
                case token::TOKEN_TYPE_OPERATOR_PREFIX:
                    if($last->peek()==null)
                    {
                        $last->pop();
                        $last->push(formula_operation_prefix::from_token($token));
                    }
                    break;
            }
        }
        if($last->peek()==null && $last->size()==1)
            return $stack;
        return null;
    }
    public function add_token(token $token): node
    {
        /** @var formula $last */
        $last=new stack();
        $last->push(null);
        $stack=null;
        switch ($token->getTokenType()) {
            case token::TOKEN_TYPE_OPERAND:
                $t=formula_operator::from_token($token);
                if($last->peek()==null)
                    $stack=$t;
                else
                    $last->peek()->add_parameter($t);
                break;
            case token::TOKEN_TYPE_ARGUMENT:
                break;
            case token::TOKEN_TYPE_FUNCTION:
                switch($token->getTokenEvent())
                {
                    case token::TOKEN_EVENT_START:
                        break;
                    case token::TOKEN_EVENT_END:
                        break;
                    default: throw new \Exception();
                }
                break;
            case token::TOKEN_TYPE_OPERATOR_PREFIX:
                if($last->peek()==null)
                {
                    $last->pop();
                    $last->push(formula_operation_prefix::from_token($token));
                }
                break;
            case token::TOKEN_TYPE_OPERATOR_INFIX:
                break;
            case token::TOKEN_TYPE_OPERATOR_POSTFIX:
                break;
            case token::TOKEN_TYPE_SUBEXPRESSION:
                break;
        }
    }
}