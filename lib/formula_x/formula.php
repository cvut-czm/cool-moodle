<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 10.03.2018
 * Time: 10:47
 */

namespace formula_x;


interface formula
{
    /**
     * @return formula[]
     */
    function parameters() : array;
    function execute() : formula_operator;

    function is_function() : bool;
    function is_operator() : bool;
    function is_operation() : bool;

    function as_operator() : formula_operator;

}