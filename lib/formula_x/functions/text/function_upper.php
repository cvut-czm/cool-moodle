<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 08.03.2018
 * Time: 22:42
 */
namespace formula_x\functions\text;

use formula_x\formula_describer;
use formula_x\formula_error;
use formula_x\formula_function;
use formula_x\formula_operator;

class function_upper extends formula_function
{
    function parameter_count()
    {
        return 1;
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('c11f29b3-d1a3-4537-8df6-04d0049963d6')
            ->lang('cs')
                ->add_abbrev('VELKÁ')
                ->set_desc('Převede text na velká písmena.')
                ->set_parameter_name(0,'text')
                ->back()
            ->lang('en')
                ->add_abbrev('UPPER')
                ->set_desc('Converts text to uppercase.')
                ->set_parameter_name(0,'text')
                ->back();
    }
    public function execute(): formula_operator
    {
        $result=$this->parameters()[0]->execute();
        $text=$result->val_as_text();
        return formula_operator::from(strtoupper($text));
    }
}