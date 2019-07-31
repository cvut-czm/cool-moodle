<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 08.03.2018
 * Time: 22:32
 */

namespace formula_x;


abstract class formula_function implements formula
{
    private $params=[];

    /**
     * @return int[]|int
    */
    abstract function parameter_count();
    /**
     * @return formula[]
     */
    function parameters(): array
    {
        return $this->params;
    }
    function execute_param(int $id)
    {
        return $this->parameters()[$id]->execute();
    }

    function abbr() : string
    {
        $class=static::class;
        $class=substr($class,strrpos($class,'\\')+1);
        $class=substr($class,strrpos($class,'_')+1);
        return strtoupper($class);
    }

    function set_parameter(int $id,$parameter)
    {
        $this->params[$id]=$parameter;
    }
    function add_parameter($parameter)
    {
        $this->params[]=$parameter;
    }

    function is_function(): bool
    {
        return true;
    }

    function is_operator(): bool
    {
        return false;
    }
    function self_describer() : formula_describer
    {
        return null;
    }

    function is_operation(): bool
    {
        return false;
    }
    function as_operator(): formula_operator
    {
        return null;
    }


    static function error_value()
    {
        return new formula_error(driver::get_string('value'));
    }
    static function error_ref()
    {
        return new formula_error(driver::get_string('ref'));
    }
    static function error_name()
    {
        return new formula_error(driver::get_string('name'));
    }
    static function error_division_by_zero()
    {
        return new formula_error(driver::get_string('div0'));
    }
    static function error_no_value_available()
    {
        return new formula_error(driver::get_string('na'));
    }
    static function error_null()
    {
        return new formula_error(driver::get_string('null'));
    }
    static function error_num()
    {
        return new formula_error(driver::get_string('num'));
    }
}