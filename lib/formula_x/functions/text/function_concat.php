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

class function_concat extends formula_function
{
    function parameter_count()
    {
        return [1,PHP_INT_MAX];
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('9b1a9a3f-94ff-41af-9736-694cbd6b4ca2')
            ->lang('cs')
                ->add_abbrev('CONCAT')
                ->set_desc('Funkce CONCAT spojuje texty z několika oblastí nebo řetězců. Nenabízí ale argumenty Oddělovač a Ignorovat_prázdné.')
                ->set_parameter_name(0,'text')
                ->back()
            ->lang('en')
                ->add_abbrev('CONCAT')
                ->set_desc('The CONCAT function combines the text from multiple ranges and/or strings, but it doesn\'t provide the delimiter or IgnoreEmpty arguments.')
                ->set_parameter_name(0,'text')
                ->back();
    }
    public function execute(): formula_operator
    {
        $output='';
        foreach ($this->parameters() as $parameter) {
            $output .= $parameter->execute()->val_as_text();
        }
        return formula_operator::from($output);
    }
}