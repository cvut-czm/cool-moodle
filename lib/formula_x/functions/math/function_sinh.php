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

class function_sinh extends formula_function
{
    function parameter_count()
    {
        return 1;
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('1e4e8b9f-2b65-43fc-ab8a-0a37f4081fa7')
            ->lang('cs')
                ->add_abbrev('SINH')
                ->set_desc('Vrátí hyperbolický sinus čísla.')
                ->set_parameter_name(0,'číslo')
                ->back()
            ->lang('en')
                ->add_abbrev('SINH')
                ->set_desc('Returns the hyperbolic sine of a number.')
                ->set_parameter_name(0,'number')
                ->back();
    }
    public function execute(): formula_operator
    {
        $result=$this->parameters()[0]->execute();
        $number=$result->val_as_number();
        return formula_operator::from(sinh($number));
    }
}