<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 08.03.2018
 * Time: 22:32
 */

namespace formula_x;

use formula_x\parser\token;

class formula_operator implements formula {
    private $val = null;

    public function __construct($value = null) {
        $this->val = $value;
    }

    public function is_null() {
        return $this->val() === null;
    }

    public function is_logical() : bool {
        if ($this->val === 0 || $this->val === 1 || $this->val === true || $this->val === false) {
            return true;
        }
        return false;
    }

    public static function from($value) : formula_operator {
        return new formula_operator($value);
    }

    public static function false() : formula_operator {
        return new formula_operator(false);
    }

    public static function true() : formula_operator {
        return new formula_operator(true);
    }

    public static function null() : formula_operator {
        return new formula_operator(null);
    }

    public static function from_token(token $token) {
        $formula = new formula_operator();
        switch ($token->getTokenEvent()) {
            case token::TOKEN_EVENT_LOGICAL:
                $formula->val = $token->getValue() == 'TRUE' ? true : false;
                break;
            case token::TOKEN_EVENT_NUMBER:
                $formula->val = floatval($token->getValue());
                break;
            case token::TOKEN_EVENT_TEXT:
                $formula->val = $token->getValue();
                break;
            case token::TOKEN_EVENT_RANGE:
                $formula->val = driver::get_variable($token->getValue());
                break;
            default:
                throw new \Exception();
        }
        return $formula;
    }

    /**
     * @return formula[]
     */
    function parameters() : array {
        return [];
    }

    function val() {
        return $this->val;
    }

    public function val_escaped()
    {
        if ($this->val === true || $this->val === false) {
            return $this->val ? "True" : "False";
        }
        if(is_numeric($this->val()))
            return $this->val();
        return '\''.$this->val().'\'';
    }

    function print_val() : string {
        if ($this->val === null) {
            return "TODO";
        }
        if ($this->val === true || $this->val === false) {
            return $this->val ? "True" : "False";
        }
        return $this->val;
    }

    function error_on_string() {
        if (!$this->is_logical() && !is_numeric($this->val) && $this->val !== null) {
            throw formula_error::value();
        }
        return $this;
    }

    function error_on_logical() : formula_operator {
        if ($this->is_logical()) {
            throw formula_error::value();
        }
        return $this;
    }

    function error_on_number() {
        if ($this->is_logical() || is_numeric($this->val)) {
            throw formula_error::value();
        }
        return $this;
    }

    /**
     * formula_operator
     */
    function val_as_number(bool $allow_null = false) {
        if ($allow_null && $this->val === null) {
            return null;
        }
        if (is_numeric($this->val)) {
            return $this->val;
        }
        if (is_bool($this->val)) {
            return $this->val ? 1 : 0;
        }
        throw new formula_error(driver::get_string('value'));
    }

    function val_as_integer() {
        if (is_numeric($this->val)) {
            return intval($this->val);
        }
        if (is_bool($this->val)) {
            return $this->val ? 1 : 0;
        }
        throw new formula_error(driver::get_string('value'));
    }

    function val_as_text() {
        return $this->val . '';
    }

    function val_as_logical(bool $only_logical = false) {
        if ($only_logical && !$this->is_logical()) {
            throw formula_error::create(formula_error::ERROR_VALUE);
        }
        return (bool) $this->val;
    }

    function execute() : formula_operator {
        return $this;
    }

    function is_function() : bool {
        return false;
    }

    function is_operator() : bool {
        return true;
    }

    function is_operation() : bool {
        return false;
    }

    function as_operator() : formula_operator {
        return $this;
    }

    function is_single_value() : bool {
        return true;
    }

    function is_array() : bool {
        return false;
    }

    function is_matrix() : bool {
        return false;
    }
}