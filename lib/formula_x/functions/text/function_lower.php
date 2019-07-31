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

class function_lower extends formula_function
{
    function parameter_count()
    {
        return 1;
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('3f21df02-a80c-44b2-afaf-81358f9fdeb4')
            ->lang('cs')
                ->add_abbrev('MALÁ')
                ->set_desc('Vrátí sinus daného úhlu.')
                ->set_parameter_name(0,'text')
                ->back()
            ->lang('en')
                ->add_abbrev('LOWER')
                ->set_desc('Converts all uppercase letters in a text string to lowercase.')
                ->set_parameter_name(0,'text')
                ->back();
    }
    public function execute(): formula_operator
    {
        $result=$this->parameters()[0]->execute();
        $text=$result->val_as_text();
        return formula_operator::from(strtolower($text));
    }
}