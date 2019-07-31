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

class function_tan extends formula_function
{
    function parameter_count()
    {
        return 1;
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('08851a40-179f-4052-b789-d7f699447401')
            ->lang('cs')
                ->add_abbrev('TG')
                ->set_desc('Vrátí tangens zadaného úhlu.')
                ->set_parameter_name(0,'číslo')
                ->back()
            ->lang('en')
                ->add_abbrev('TAN')
                ->set_desc('Returns the tangent of the given angle.')
                ->set_parameter_name(0,'number')
                ->back();
    }
    public function execute(): formula_operator
    {
        $result=$this->parameters()[0]->execute();
        $number=$result->val_as_number();
        return formula_operator::from(tan($number));
    }
}