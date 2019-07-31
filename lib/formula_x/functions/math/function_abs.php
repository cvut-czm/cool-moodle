<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 08.03.2018
 * Time: 22:42
 */
namespace formula_x\functions\math;

use formula_x\formula_describer;
use formula_x\formula_error;
use formula_x\formula_function;
use formula_x\formula_operator;

class function_abs extends formula_function
{

    /**
     * How many parameters this function can take as input. (Inclusively)
     *
     * @example [2;10] Function take at least 2 parameters and up to 10 parameters
     * @return int|int[]
    */
    function parameter_count()
    {
        return 1; // Can be integer or array of two integer [minimum_count,maximum_count]
    }
    /**
     * Provide describer for this function in different languages.
     *
     * @return formula_describer
    */
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('3420200f-5628-4e8c-99da-c99d7c87713c') // ID of article for ABS on support.office.com
            ->lang('cs')
                ->add_abbrev('ABS')  // Abbrev is used in formula. '=ABS(-10)' => ABS is abbrev.
                ->set_desc('Vrátí absolutní hodnotu čísla.')  // Short description of function
                ->set_parameter_name(0,'číslo') // Name of parameter
                ->back()
            ->lang('en')
                ->add_abbrev('ABS')
                ->set_desc('Return absolute value of number.')
                ->set_parameter_name(0,'number')
                ->back();
    }
    /**
     * Execute function
     *
     * @return formula_operator
     * @throws formula_error
    */
    public function execute(): formula_operator
    {
        /**
         * We firstly need to execute/calculate function parameters.
         * Function parameters are not calculated automatically,
         * mostly because some functions don´t use all of them (decision functions)
         * or handle their errors (IFERROR function, etc.)
        */
        $result=$this->parameters()[0]->execute();
        $number=$result->val_as_number();            //Read operator as number, if it is not a number or cannot be converted to number (string,array,matrix) that throws value error
        return formula_operator::from(abs($number)); //We return new formula_operator as absolute value of number
    }
}