<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 03/06/2018
 * Time: 11:17
 */

namespace formula_x\functions\ctu_crypto;


use formula_x\formula_function;
use formula_x\formula_operator;

class function_modpow extends formula_function
{

    function execute(): formula_operator
    {
        $number=$this->execute_param(0)->val_as_integer();
        $exponent=$this->execute_param(1)->val_as_integer();
        $modulo=$this->execute_param(2)->val_as_integer();
        return formula_operator::from($this->powmod($number,$exponent,$modulo));
    }
    private function powmod($number, $exp, $mod)
    {
        $res = 1;
        $number = $number % $mod;

        while ($exp > 0)
        {
            if ($exp & 1)
                $res = ($res * $number) % $mod;
            $exp = $exp >> 1;
            $number = ($number * $number) % $mod;
        }
        return $res;
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return 3;
    }
}