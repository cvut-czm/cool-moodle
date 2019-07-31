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

class function_roundup extends formula_function
{
    function parameter_count()
    {
        return 2;
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('f8bc9b23-e795-47db-8703-db171d0c42a7')
            ->lang('cs')
                ->add_abbrev('ROUNDUP')
                ->set_desc('Zaokrouhlí číslo nahoru, směrem od nuly.')
                ->set_parameter_name(0,'číslo')
                ->back()
            ->lang('en')
                ->add_abbrev('ROUNDUP')
                ->set_desc('Rounds a number up, away from 0 (zero).')
                ->set_parameter_name(0,'number')
                ->back();
    }
    public function execute(): formula_operator
    {
        $result=$this->parameters()[0]->execute();
        $precision=$this->parameters()[1]->execute();
        $pow=pow(10,abs($precision->val_as_number()));
        $number=$result->val_as_number();
        return formula_operator::from(ceil($number*$pow)/$pow);
    }
}