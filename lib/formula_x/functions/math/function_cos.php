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

class function_cos extends formula_function
{
    function parameter_count()
    {
        return 1;
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('0fb808a5-95d6-4553-8148-22aebdce5f05')
            ->lang('cs')
                ->add_abbrev('COS')
                ->set_desc('Vrátí kosinus zadaného úhlu.')
                ->set_parameter_name(0,'číslo')
                ->back()
            ->lang('en')
                ->add_abbrev('COS')
                ->set_desc('Returns the cosine of the given angle.')
                ->set_parameter_name(0,'number')
                ->back();
    }
    public function execute(): formula_operator
    {
        $result=$this->parameters()[0]->execute();
        $number=$result->val_as_number();
        return formula_operator::from(cos($number));
    }
}