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

class function_trim extends formula_function
{
    function parameter_count()
    {
        return 1;
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('410388fa-c5df-49c6-b16c-9e5630b479f9')
            ->lang('cs')
                ->add_abbrev('PROČISTIT')
                ->set_desc('Odstraní nadbytečné mezery v textu tak, aby byla slova oddělena pouze jednou mezerou. Funkce PROČISTIT se používá u textů importovaných z jiných aplikací, které mohou obsahovat velký počet nadbytečných mezer.')
                ->set_parameter_name(0,'text')
                ->back()
            ->lang('en')
                ->add_abbrev('TRIM')
                ->set_desc('Removes all spaces from text except for single spaces between words. Use TRIM on text that you have received from another application that may have irregular spacing.')
                ->set_parameter_name(0,'text')
                ->back();
    }
    public function execute(): formula_operator
    {
        $result=$this->parameters()[0]->execute();
        $text=$result->val_as_text();
        $text=trim($text);
        while (strpos($text,'  '))
            $text=str_replace('  ',' ',$text);
        return formula_operator::from($text);
    }
}