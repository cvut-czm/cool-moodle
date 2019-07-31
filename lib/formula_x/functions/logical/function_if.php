<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 08.03.2018
 * Time: 22:42
 */
namespace formula_x\functions\logical;

use formula_x\formula_describer;
use formula_x\formula_error;
use formula_x\formula_function;
use formula_x\formula_operator;

class function_if extends formula_function
{
    function parameter_count()
    {
        return [2,3];
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('69aed7c9-4e8a-4755-a9bc-aa8bbff73be2')
            ->lang('cs')
                ->add_abbrev('KDYŽ')
                ->set_desc('Jednou z nejoblíbenějších funkcí Excelu je funkce KDYŽ, která umožňuje logicky porovnávat hodnotu s očekáváním.')
                ->set_parameter_name(0,'když')
                ->set_parameter_name(1,'pak')
                ->set_parameter_name(2,'jinak')
                ->back()
            ->lang('en')
                ->add_abbrev('IF')
                ->set_desc('The IF function is one of the most popular functions in Excel, and it allows you to make logical comparisons between a value and what you expect.')
                ->set_parameter_name(0,'if')
                ->set_parameter_name(1,'then')
                ->set_parameter_name(2,'otherwise')
                ->back();
    }
    public function execute(): formula_operator
    {
        $if=$this->parameters()[0]->execute()->error_on_string()->val_as_logical();
        if($if)
            return formula_operator::from($this->parameters()[1]->execute()->val());
        if(count($this->parameters())==3)
            return formula_operator::from($this->parameters()[2]->execute()->val());
        return formula_error::value();

    }
}