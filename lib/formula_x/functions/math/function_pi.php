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

class function_pi extends formula_function
{
    function parameter_count()
    {
        return 0;
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('264199d0-a3ba-46b8-975a-c4a04608989b')
            ->lang('cs')
                ->add_abbrev('PI')
                ->set_desc('Vrátí číslo 3,14159265358979, matematickou konstantu pí, s přesností na 15 platných číslic.')
                ->back()
            ->lang('en')
                ->add_abbrev('PI')
                ->set_desc('Returns the number 3.14159265358979, the mathematical constant pi, accurate to 15 digits.')
                ->back();
    }
    public function execute(): formula_operator
    {
        return formula_operator::from(3.14159265358979);
    }
}