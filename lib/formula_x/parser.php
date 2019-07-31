<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 09.03.2018
 * Time: 5:35
 */

namespace formula_x;

use formula_x\helper\ReorderHelper;
use formula_x\parser\token;

class parser
{
    /* Character constants */
    const QUOTE_DOUBLE = '"';
    const QUOTE_SINGLE = '\'';
    const BRACKET_CLOSE = ']';
    const BRACKET_OPEN = '[';
    const BRACE_OPEN = '{';
    const BRACE_CLOSE = '}';
    const PAREN_OPEN = '(';
    const PAREN_CLOSE = ')';
    const SEMICOLON = ';';
    const WHITESPACE = ' ';
    const COMMA = ',';
    const ERROR_START = '#';

    const OPERATORS_SN = "+-";
    const OPERATORS_INFIX = "+-*/^&=><";
    const OPERATORS_POSTFIX = "%";

    /**
     * Formula
     *
     * @var string
     */
    private $_formula;

    /**
     * Tokens
     *
     * @var token[]
     */
    private $_tokens = array();

    /**
     * Create a new parse
     *
     * @param    string $pFormula Formula to parse
     * @throws    Exception
     */
    public function __construct(string $pFormula = '')
    {
        $this->_formula = trim($pFormula);
        $this->_parseToTokens();
    }

    /**
     * Get Formula
     *
     * @return string
     */
    public function getFormula()
    {
        return $this->_formula;
    }
/*
    public function getExecutable(int $start=0,token $end_at=null) : ?formula
    {
        $tokens=$this->getTokens();
        $c=$start;
        $last=new stack();
        $builder=new stack();
        $stack=null;
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
                    if($last->empty())
                        $last->push(formula_operation_prefix::from_token($token));
                    else
                        $last->peek()->add_parameter(formula_operation_prefix::from_token($token));
                    break;
                case token::TOKEN_TYPE_OPERATOR_INFIX:
                    break;

            }
        }
        if($last->size()>1)
            throw new \Exception();
        if($last->peek()==null)
            return $stack;
        return $last->pop();
    }*/

    /**
     * Get Token
     *
     * @param    int $pId Token id
     * @return    string
     * @throws  Exception
     */
    public function getToken($pId = 0)
    {
        if (isset($this->_tokens[$pId])) {
            return $this->_tokens[$pId];
        } else {
            throw new Exception("Token with id $pId does not exist.");
        }
    }

    /**
     * Get Token count
     *
     * @return string
     */
    public function getTokenCount()
    {
        return count($this->_tokens);
    }

    /**
     * Get Tokens
     *
     * @return token[]
     */
    public function getTokens()
    {
        return $this->_tokens;
    }

    public function getExecutable()
    {
        $tokens=$this->getTokens();
        $tokens= ReorderHelper::excel_to_postfix($tokens);
        return ReorderHelper::postfix_to_formula($tokens);
    }

    /**
     * Parse to tokens
     */
    private function _parseToTokens()
    {
        $tokens1 = array();
        $tokens2 = array();
        $stack = array();
        $inString = false;
        $inPath = false;
        $inRange = false;
        $inError = false;

        $index = 0;
        $value = '';

        $ERRORS = array("#NULL!", "#DIV/0!", "#VALUE!", "#REF!", "#NAME?", "#NUM!", "#N/A");
        $COMPARATORS_MULTI = array(">=", "<=", "<>");

        $token = null;
        $previousToken = null;
        $nextToken = null;

        while ($index < strlen($this->_formula)) {
            // state-dependent character evaluation (order is important)

            // double-quoted strings
            // embeds are doubled
            // end marks token
            if ($inString) {
                if (substr($this->_formula, $index, 1) == parser::QUOTE_DOUBLE) {
                    if ((($index + 2) <= strlen($this->_formula)) && (substr($this->_formula, $index + 1, 1) == parser::QUOTE_DOUBLE)) {
                        $value .= parser::QUOTE_DOUBLE;
                        $index++;
                    } else {
                        $inString = false;
                        array_push(
                            $tokens1,
                            new token($value, token::TOKEN_TYPE_OPERAND, token::TOKEN_EVENT_TEXT)
                        );
                        $value = "";
                    }
                } else {
                    $value .= substr($this->_formula, $index, 1);
                }
                $index++;
                continue;
            }

            // single-quoted strings (links)
            // embeds are double
            // end does not mark a token
            if ($inPath) {
                if (substr($this->_formula, $index, 1) == parser::QUOTE_SINGLE) {
                    if ((($index + 2) <= strlen($this->_formula)) && (substr($this->_formula, $index + 1, 1) == parser::QUOTE_SINGLE)) {
                        $value .= parser::QUOTE_SINGLE;
                        $index++;
                    } else {
                        $inPath = false;
                    }
                } else {
                    $value .= substr($this->_formula, $index, 1);
                }
                $index++;
                continue;
            }

            // bracked strings (R1C1 range index or linked workbook name)
            // no embeds (changed to "()" by Excel)
            // end does not mark a token
            if ($inRange) {
                if (substr($this->_formula, $index, 1) == parser::BRACKET_CLOSE) {
                    $inRange = false;
                }
                $value .= substr($this->_formula, $index, 1);
                $index++;
                continue;
            }

            // error values
            // end marks a token, determined from absolute list of values
            if ($inError) {
                $value .= substr($this->_formula, $index, 1);
                $index++;
                if (in_array($value, $ERRORS)) {
                    $inError = false;
                    array_push(
                        $tokens1,
                        new token($value, token::TOKEN_TYPE_OPERAND, token::TOKEN_EVENT_ERROR)
                    );
                    $value = "";
                }
                continue;
            }

            // scientific notation check
            if (strpos(parser::OPERATORS_SN, substr($this->_formula, $index, 1)) !== false) {
                if (strlen($value) > 1) {
                    if (preg_match("/^[1-9]{1}(\.[0-9]+)?E{1}$/", substr($this->_formula, $index, 1)) != 0) {
                        $value .= substr($this->_formula, $index, 1);
                        $index++;
                        continue;
                    }
                }
            }

            // independent character evaluation (order not important)

            // establish state-dependent character evaluations
            if (substr($this->_formula, $index, 1) == parser::QUOTE_DOUBLE) {
                if (strlen($value > 0)) {  // unexpected
                    array_push(
                        $tokens1,
                        new token($value, token::TOKEN_TYPE_UNKNOWN)
                    );
                    $value = "";
                }
                $inString = true;
                $index++;
                continue;
            }

            if (substr($this->_formula, $index, 1) == parser::QUOTE_SINGLE) {
                if (strlen($value) > 0) { // unexpected
                    array_push(
                        $tokens1,
                        new token($value, token::TOKEN_TYPE_UNKNOWN)
                    );
                    $value = "";
                }
                $inPath = true;
                $index++;
                continue;
            }

            if (substr($this->_formula, $index, 1) == parser::BRACKET_OPEN) {
                $inRange = true;
                $value .= parser::BRACKET_OPEN;
                $index++;
                continue;
            }

            if (substr($this->_formula, $index, 1) == parser::ERROR_START) {
                if (strlen($value) > 0) { // unexpected
                    array_push(
                        $tokens1,
                        new token($value, token::TOKEN_TYPE_UNKNOWN)
                    );
                    $value = "";
                }
                $inError = true;
                $value .= parser::ERROR_START;
                $index++;
                continue;
            }

            // mark start and end of arrays and array rows
            if (substr($this->_formula, $index, 1) == parser::BRACE_OPEN) {
                if (strlen($value) > 0) { // unexpected
                    array_push(
                        $tokens1,
                        new token($value, token::TOKEN_TYPE_UNKNOWN)
                    );
                    $value = "";
                }

                $tmp = new token("ARRAY", token::TOKEN_TYPE_FUNCTION, token::TOKEN_EVENT_START);
                array_push($tokens1, $tmp);
                array_push($stack, clone $tmp);

                $tmp = new token("ARRAYROW", token::TOKEN_TYPE_FUNCTION, token::TOKEN_EVENT_START);
                array_push($tokens1, $tmp);
                array_push($stack, clone $tmp);

                $index++;
                continue;
            }

            if (substr($this->_formula, $index, 1) == parser::SEMICOLON) {
                if (strlen($value) > 0) {
                    array_push(
                        $tokens1,
                        new token($value, token::TOKEN_TYPE_OPERAND)
                    );
                    $value = "";
                }

                $tmp = array_pop($stack);
                $tmp->setValue("");
                $tmp->setTokenEvent(token::TOKEN_EVENT_END);
                //array_push($tokens1, $tmp);

                $tmp = new token(",", token::TOKEN_TYPE_ARGUMENT);
                array_push($tokens1, $tmp);

                $tmp = new token("ARRAYROW", token::TOKEN_TYPE_FUNCTION, token::TOKEN_EVENT_START);
                //array_push($tokens1, $tmp);
                array_push($stack, clone $tmp);

                $index++;
                continue;
            }

            if (substr($this->_formula, $index, 1) == parser::BRACE_CLOSE) {
                if (strlen($value) > 0) {
                    array_push(
                        $tokens1,
                        new token($value, token::TOKEN_TYPE_OPERAND)
                    );
                    $value = "";
                }

                $tmp = array_pop($stack);
                $tmp->setValue("");
                $tmp->setTokenEvent(token::TOKEN_EVENT_END);
                array_push($tokens1, $tmp);

                $tmp = array_pop($stack);
                $tmp->setValue("");
                $tmp->setTokenEvent(token::TOKEN_EVENT_END);
                array_push($tokens1, $tmp);

                $index++;
                continue;
            }

            // trim white-space
            if (substr($this->_formula, $index, 1) == parser::WHITESPACE) {
                if (strlen($value) > 0) {
                    array_push(
                        $tokens1,
                        new token($value, token::TOKEN_TYPE_OPERAND)
                    );
                    $value = "";
                }
                array_push(
                    $tokens1,
                    new token("", token::TOKEN_TYPE_WHITESPACE)
                );
                $index++;
                while ((substr($this->_formula, $index, 1) == parser::WHITESPACE) && ($index < strlen($this->_formula))) {
                    $index++;
                }
                continue;
            }

            // multi-character comparators
            if (($index + 2) <= strlen($this->_formula)) {
                if (in_array(substr($this->_formula, $index, 2), $COMPARATORS_MULTI)) {
                    if (strlen($value) > 0) {
                        array_push(
                            $tokens1,
                            new token($value, token::TOKEN_TYPE_OPERAND)
                        );
                        $value = "";
                    }
                    array_push(
                        $tokens1,
                        new token(substr($this->_formula, $index, 2), token::TOKEN_TYPE_OPERATOR_INFIX, token::TOKEN_EVENT_LOGICAL)
                    );
                    $index += 2;
                    continue;
                }
            }

            // standard infix operators
            if (strpos(parser::OPERATORS_INFIX, substr($this->_formula, $index, 1)) !== false) {
                if (strlen($value) > 0) {
                    array_push(
                        $tokens1,
                        new token($value, token::TOKEN_TYPE_OPERAND)
                    );
                    $value = "";
                }
                array_push(
                    $tokens1,
                    new token(substr($this->_formula, $index, 1), token::TOKEN_TYPE_OPERATOR_INFIX)
                );
                $index++;
                continue;
            }

            // standard postfix operators (only one)
            if (strpos(parser::OPERATORS_POSTFIX, substr($this->_formula, $index, 1)) !== false) {
                if (strlen($value) > 0) {
                    array_push(
                        $tokens1,
                        new token($value, token::TOKEN_TYPE_OPERAND)
                    );
                    $value = "";
                }
                array_push(
                    $tokens1,
                    new token(substr($this->_formula, $index, 1), token::TOKEN_TYPE_OPERATOR_POSTFIX)
                );
                $index++;
                continue;
            }

            // start subexpression or function 
            if (substr($this->_formula, $index, 1) == parser::PAREN_OPEN) {
                if (strlen($value) > 0) {
                    $tmp = new token($value, token::TOKEN_TYPE_FUNCTION, token::TOKEN_EVENT_START);
                    array_push($tokens1, $tmp);
                    array_push($stack, clone $tmp);
                    $value = "";
                } else {
                    $tmp = new token("", token::TOKEN_TYPE_SUBEXPRESSION, token::TOKEN_EVENT_START);
                    array_push($tokens1, $tmp);
                    array_push($stack, clone $tmp);
                }
                $index++;
                continue;
            }

            // function, subexpression, or array parameters, or operand unions
            if (substr($this->_formula, $index, 1) == parser::COMMA) {
                if (strlen($value) > 0) {
                    array_push(
                        $tokens1,
                        new token($value, token::TOKEN_TYPE_OPERAND)
                    );
                    $value = "";
                }

                $tmp = array_pop($stack);
                $tmp->setValue("");
                $tmp->setTokenEvent(token::TOKEN_EVENT_END);
                array_push($stack, $tmp);

                if ($tmp->getTokenType() == token::TOKEN_TYPE_FUNCTION) {
                    array_push(
                        $tokens1,
                        new token(",", token::TOKEN_TYPE_OPERATOR_INFIX, token::TOKEN_EVENT_UNION)
                    );
                } else {
                    array_push(
                        $tokens1,
                        new token(",", token::TOKEN_TYPE_ARGUMENT)
                    );
                }
                $index++;
                continue;
            }

            // stop subexpression
            if (substr($this->_formula, $index, 1) == parser::PAREN_CLOSE) {
                if (strlen($value) > 0) {
                    array_push(
                        $tokens1,
                        new token($value, token::TOKEN_TYPE_OPERAND)
                    );
                    $value = "";
                }

                $tmp = array_pop($stack);
                $tmp->setValue("");
                $tmp->setTokenEvent(token::TOKEN_EVENT_END);
                array_push($tokens1, $tmp);

                $index++;
                continue;
            }

            // token accumulation
            $value .= substr($this->_formula, $index, 1);
            $index++;
        }

        // dump remaining accumulation
        if (strlen($value) > 0) {
            array_push(
                $tokens1,
                new token($value, token::TOKEN_TYPE_OPERAND)
            );
        }

        // move tokenList to new set, excluding unnecessary white-space tokens and converting necessary ones to intersections
        for ($i = 0; $i < count($tokens1); $i++) {
            $token = $tokens1[$i];
            if (isset($tokens1[$i - 1])) {
                $previousToken = $tokens1[$i - 1];
            } else {
                $previousToken = null;
            }
            if (isset($tokens1[$i + 1])) {
                $nextToken = $tokens1[$i + 1];
            } else {
                $nextToken = null;
            }

            if (is_null($token)) {
                continue;
            }

            if ($token->getTokenType() != token::TOKEN_TYPE_WHITESPACE) {
                array_push($tokens2, $token);
                continue;
            }

            if (is_null($previousToken)) {
                continue;
            }

            if (!(
                (($previousToken->getTokenType() == token::TOKEN_TYPE_FUNCTION) && ($previousToken->getTokenEvent() == token::TOKEN_EVENT_END)) ||
                (($previousToken->getTokenType() == token::TOKEN_TYPE_SUBEXPRESSION) && ($previousToken->getTokenEvent() == token::TOKEN_EVENT_END)) ||
                ($previousToken->getTokenType() == token::TOKEN_TYPE_OPERAND)
            )) {
                continue;
            }

            if (is_null($nextToken)) {
                continue;
            }

            if (!(
                (($nextToken->getTokenType() == token::TOKEN_TYPE_FUNCTION) && ($nextToken->getTokenEvent() == token::TOKEN_EVENT_START)) ||
                (($nextToken->getTokenType() == token::TOKEN_TYPE_SUBEXPRESSION) && ($nextToken->getTokenEvent() == token::TOKEN_EVENT_START)) ||
                ($nextToken->getTokenType() == token::TOKEN_TYPE_OPERAND)
            )) {
                continue;
            }

            array_push(
                $tokens2,
                new token($value, token::TOKEN_TYPE_OPERATOR_INFIX, token::TOKEN_EVENT_INTERSECTION)
            );
        }

        // move tokens to final list, switching infix "-" operators to prefix when appropriate, switching infix "+" operators 
        // to noop when appropriate, identifying operand and infix-operator subtypes, and pulling "@" from function names
        $this->_tokens = array();

        for ($i = 0; $i < count($tokens2); $i++) {
            $token = $tokens2[$i];
            if (isset($tokens2[$i - 1])) {
                $previousToken = $tokens2[$i - 1];
            } else {
                $previousToken = null;
            }
            if (isset($tokens2[$i + 1])) {
                $nextToken = $tokens2[$i + 1];
            } else {
                $nextToken = null;
            }

            if (is_null($token)) {
                continue;
            }

            if ($token->getTokenType() == token::TOKEN_TYPE_OPERATOR_INFIX && $token->getValue() == "-") {
                if ($i == 0) {
                    $token->setTokenType(token::TOKEN_TYPE_OPERATOR_PREFIX);
                } else if (
                    (($previousToken->getTokenType() == token::TOKEN_TYPE_FUNCTION) && ($previousToken->getTokenEvent() == token::TOKEN_EVENT_END)) ||
                    (($previousToken->getTokenType() == token::TOKEN_TYPE_SUBEXPRESSION) && ($previousToken->getTokenEvent() == token::TOKEN_EVENT_END)) ||
                    ($previousToken->getTokenType() == token::TOKEN_TYPE_OPERATOR_POSTFIX) ||
                    ($previousToken->getTokenType() == token::TOKEN_TYPE_OPERAND)
                ) {
                    $token->setTokenEvent(token::TOKEN_EVENT_MATH);
                } else {
                    $token->setTokenType(token::TOKEN_TYPE_OPERATOR_PREFIX);
                }

                array_push($this->_tokens, $token);
                continue;
            }

            if ($token->getTokenType() == token::TOKEN_TYPE_OPERATOR_INFIX && $token->getValue() == "+") {
                if ($i == 0) {
                    continue;
                } else if (
                    (($previousToken->getTokenType() == token::TOKEN_TYPE_FUNCTION) && ($previousToken->getTokenEvent() == token::TOKEN_EVENT_END)) ||
                    (($previousToken->getTokenType() == token::TOKEN_TYPE_SUBEXPRESSION) && ($previousToken->getTokenEvent() == token::TOKEN_EVENT_END)) ||
                    ($previousToken->getTokenType() == token::TOKEN_TYPE_OPERATOR_POSTFIX) ||
                    ($previousToken->getTokenType() == token::TOKEN_TYPE_OPERAND)
                ) {
                    $token->setTokenEvent(token::TOKEN_EVENT_MATH);
                } else {
                    continue;
                }

                array_push($this->_tokens, $token);
                continue;
            }

            if ($token->getTokenType() == token::TOKEN_TYPE_OPERATOR_INFIX && $token->getTokenEvent() == token::TOKEN_EVENT_NOTHING) {
                if (strpos("<>=", substr($token->getValue(), 0, 1)) !== false) {
                    $token->setTokenEvent(token::TOKEN_EVENT_LOGICAL);
                } else if ($token->getValue() == "&") {
                    $token->setTokenEvent(token::TOKEN_EVENT_CONCATENATION);
                } else {
                    $token->setTokenEvent(token::TOKEN_EVENT_MATH);
                }

                array_push($this->_tokens, $token);
                continue;
            }

            if ($token->getTokenType() == token::TOKEN_TYPE_OPERAND && $token->getTokenEvent() == token::TOKEN_EVENT_NOTHING) {
                if (!is_numeric($token->getValue())) {
                    if (strtoupper($token->getValue()) == driver::get_string('true') || strtoupper($token->getValue() == driver::get_string('false'))) {
                        $token->setTokenEvent(token::TOKEN_EVENT_LOGICAL);
                        $token->setValue(strtoupper($token->getValue())==driver::get_string('true')?'TRUE':'FALSE');
                    } else {
                        $token->setTokenEvent(token::TOKEN_EVENT_RANGE);
                    }
                } else {
                    $token->setTokenEvent(token::TOKEN_EVENT_NUMBER);
                }

                array_push($this->_tokens, $token);
                continue;
            }

            if ($token->getTokenType() == token::TOKEN_TYPE_FUNCTION) {
                if (strlen($token->getValue() > 0)) {
                    if (substr($token->getValue(), 0, 1) == "@") {
                        $token->setValue(substr($token->getValue(), 1));
                    }
                }
            }

            array_push($this->_tokens, $token);
        }
    }
}