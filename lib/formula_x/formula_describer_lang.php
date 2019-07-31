<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 11.03.2018
 * Time: 11:35
 */

namespace formula_x;


class formula_describer_lang
{
    private $parent;
    private $abbrevs=[];
    private $params_desc=[];
    private $desc;
    public function __construct(formula_describer $describer)
    {
        $this->parent=$describer;
    }
    public function back() : formula_describer
    {
        return $this->parent;
    }

    public function add_abbrev(string $abbrev) : formula_describer_lang
    {
        $this->abbrevs[]=strtoupper($abbrev);
        return $this;
    }
    public function set_desc(string $description) : formula_describer_lang
    {
        $this->desc=$description;
        return $this;
    }
    public function get_desc() : string
    {
        return $this->desc;
    }
    public function get_abbrev() : string
    {
        return join(', ',$this->abbrevs);
    }
    public function set_parameter_name(int $param, string $description) : formula_describer_lang
    {
        $this->params_desc[$param]=$description;
        return $this;
    }
}