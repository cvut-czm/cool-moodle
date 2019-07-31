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

class function_cot extends formula_function
{
    function parameter_count()
    {
        return 1;
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('c446f34d-6fe4-40dc-84f8-cf59e5f5e31a')
            ->lang('cs')
                ->add_abbrev('COT')
                ->set_desc('Vrátí kotangens úhlu zadaného v radiánech.')
                ->set_parameter_name(0,'číslo')
                ->back()
            ->lang('en')
                ->add_abbrev('COT')
                ->set_desc('Return the cotangent of an angle specified in radians.')
                ->set_parameter_name(0,'number')
                ->back();
    }
    public function execute(): formula_operator
    {
        $result=$this->parameters()[0]->execute();
        $number=$result->val_as_number();
        $tan=tan($number);
        if($tan==0)
            throw self::error_division_by_zero();
        return formula_operator::from(1/$tan);
    }
}