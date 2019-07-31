<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 08.03.2018
 * Time: 22:31
 */

namespace formula_x;


class driver_option
{
    public $lang='cs';

    public $function_tags=['('=>')'];
    public $function_param_separator=[','];

    public $operator_unary=['-','+'];
    public $operator_binary=['+','-','*','/','^','%'];
    public $operator_comparison=['=','<>','<','>','<=','>='];

    public $string_tags=['"',"'"];
    public $string_escape_character=['\\'];
}