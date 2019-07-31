<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 03/06/2018
 * Time: 10:13
 */

namespace formula_x\functions\ctu_crypto;


use formula_x\formula_function;
use formula_x\formula_operator;

class function_isprime extends formula_function
{

    function execute(): formula_operator
    {
        $number=$this->parameters()[0]->execute()->val_as_integer();
        $order=count($this->parameters())==1?10:$this->parameters()[1]->execute()->val_as_integer();
        return formula_operator::from($this->is_prime($number,$order));
    }
    /**
     * Taken/credits go to: https://rosettacode.org/wiki/Miller%E2%80%93Rabin_primality_test#PHP
    */
    function is_prime($n, $k) {
        if ($n == 2)
            return true;
        if ($n < 2 || $n % 2 == 0)
            return false;

        $d = $n - 1;
        $s = 0;

        while ($d % 2 == 0) {
            $d /= 2;
            $s++;
        }

        for ($i = 0; $i < $k; $i++) {
            $a = rand(2, $n-1);

            $x = bcpowmod($a, $d, $n);
            if ($x == 1 || $x == $n-1)
                continue;

            for ($j = 1; $j < $s; $j++) {
                $x = bcmod(bcmul($x, $x), $n);
                if ($x == 1)
                    return false;
                if ($x == $n-1)
                    continue 2;
            }
            return false;
        }
        return true;
    }

    /**
     * @return int[]|int
     */
    function parameter_count()
    {
        return 1;
    }
}