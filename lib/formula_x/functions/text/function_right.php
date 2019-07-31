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

class function_right extends formula_function
{
    function parameter_count()
    {
        return [1,2];
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('240267ee-9afa-4639-a02b-f19e1786cf2f')
            ->lang('cs')
                ->add_abbrev('ZLEVA')
                ->set_desc('Funkce ZPRAVA vrátí zadaný počet znaků od konce textového řetězce.')
                ->set_parameter_name(0,'text')
            ->set_parameter_name(0,'znaky')
                ->back()
            ->lang('en')
                ->add_abbrev('RIGHT')
                ->set_desc('RIGHT returns the last character or characters in a text string, based on the number of characters you specify.')
                ->set_parameter_name(0,'text')
                ->set_parameter_name(0,'num_chars')
                ->back();
    }
    public function execute(): formula_operator
    {
        $result=$this->parameters()[0]->execute();
        $num=1;
        if(count($this->parameters())==2) {
            $result2 = $this->parameters()[1]->execute();
            $num=$result2->val_as_number();
        }
        $text=$result->val_as_text();
        if($num<0)
            throw self::error_value();
        return formula_operator::from(substr($text,0,-$num));
    }
}