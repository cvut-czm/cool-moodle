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

class function_sin extends formula_function
{
    function parameter_count()
    {
        return 1;
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('cf0e3432-8b9e-483c-bc55-a76651c95602')
            ->lang('cs')
                ->add_abbrev('SIN')
                ->set_desc('Vrátí sinus daného úhlu.')
                ->set_parameter_name(0,'číslo')
                ->back()
            ->lang('en')
                ->add_abbrev('SIN')
                ->set_desc('Returns the sine of the given angle.')
                ->set_parameter_name(0,'number')
                ->back();
    }
    public function execute(): formula_operator
    {
        $result=$this->parameters()[0]->execute();
        $number=$result->val_as_number();
        return formula_operator::from(sin($number));
    }
}