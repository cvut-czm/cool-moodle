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

class function_left extends formula_function
{
    function parameter_count()
    {
        return [1,2];
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('9203d2d2-7960-479b-84c6-1ea52b99640c')
            ->lang('cs')
                ->add_abbrev('ZLEVA')
                ->set_desc('Funkce ZLEVA vrátí první znak nebo znaky v textovém řetězci na základě zadaného počtu znaků.')
                ->set_parameter_name(0,'text')
            ->set_parameter_name(0,'znaky')
                ->back()
            ->lang('en')
                ->add_abbrev('LEFT')
                ->set_desc('LEFT returns the first character or characters in a text string, based on the number of characters you specify.')
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
        return formula_operator::from(substr($text,0,$num));
    }
}