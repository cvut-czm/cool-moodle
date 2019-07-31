<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 08.03.2018
 * Time: 22:34
 */

namespace formula_x;


use Throwable;

class formula_error extends \Exception implements formula
{
    public const ERROR_DIV_BY_ZERO='div0';
    public const ERROR_NAME='name';
    public const ERROR_NA='na';
    public const ERROR_NULL='null';
    public const ERROR_NUM='num';
    public const ERROR_REF='ref';
    public const ERROR_VALUE='value';

    public static function div_by_zero() : formula_error
    {
        return new formula_error(driver::get_string(self::ERROR_DIV_BY_ZERO));
    }
    public static function name() : formula_error
    {
        return new formula_error(driver::get_string(self::ERROR_NAME));
    }
    public static function na() : formula_error
    {
        return new formula_error(driver::get_string(self::ERROR_NA));
    }
    public static function null() : formula_error
    {
        return new formula_error(driver::get_string(self::ERROR_NULL));
    }
    public static function num() : formula_error
    {
        return new formula_error(driver::get_string(self::ERROR_NUM));
    }
    public static function ref() : formula_error
    {
        return new formula_error(driver::get_string(self::ERROR_REF));
    }
    public static function value() : formula_error
    {
        return new formula_error(driver::get_string(self::ERROR_VALUE));
    }
    public static function create(string $code) : formula_error
    {
        return new formula_error(driver::get_string($code));
    }
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function execute(): formula_operator
    {
        throw $this;
    }
    public function val()
    {
        return '#'.$this->message.'!';
    }
    public function val_escaped()
    {
        return $this->val();
    }
    /**
     * @return formula[]
     */
    function parameters(): array
    {
        return [];
    }

    function is_function(): bool
    {
        // TODO: Implement is_function() method.
    }

    function is_operator(): bool
    {
        // TODO: Implement is_operator() method.
    }

    function is_operation(): bool
    {
        // TODO: Implement is_operation() method.
    }

    function as_operator(): formula_operator
    {
        // TODO: Implement as_operator() method.
    }
}