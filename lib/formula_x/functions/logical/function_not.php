<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 29/05/2018
 * Time: 12:58
 */

namespace formula_x\functions\logical;

use formula_x\formula_function;
use formula_x\formula_operator;

class function_not extends formula_function {
    function parameter_count() {
        return 1;
    }

    function execute() : formula_operator {
        return formula_operator::from(!$this->parameters()[0]->execute()->error_on_string()->val_as_logical(false));
    }
}