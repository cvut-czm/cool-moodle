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

class function_exact extends formula_function
{
    function parameter_count()
    {
        return 2;
    }
    function self_describer() : formula_describer
    {
        return formula_describer::create()
            ->set_office_page('d3087698-fc15-4a15-9631-12575cf29926')
            ->lang('cs')
                ->add_abbrev('STEJNÉ')
                ->set_desc('Porovná dva textové řetězce a vrátí logickou hodnotu PRAVDA, pokud se přesně shodují, v opačném případě hodnotu NEPRAVDA. Funkce STEJNÉ rozlišuje velká a malá písmena, ale ignoruje rozdíly ve formátování. Tuto funkci lze použít pro testování textu, který vkládáte do dokumentu.')
                ->set_parameter_name(0,'text')
                ->back()
            ->lang('en')
                ->add_abbrev('EXACT')
                ->set_desc('Compares two text strings and returns TRUE if they are exactly the same, FALSE otherwise. EXACT is case-sensitive but ignores formatting differences. Use EXACT to test text being entered into a document.')
                ->set_parameter_name(0,'text')
                ->back();
    }
    public function execute(): formula_operator
    {
        $result1=$this->parameters()[0]->execute();
        $text1=$result1->val_as_text();
        $result2=$this->parameters()[0]->execute();
        $text2=$result2->val_as_text();
        return formula_operator::from($text1==$text2);
    }
}