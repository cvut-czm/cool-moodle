<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 11.03.2018
 * Time: 11:34
 */

namespace formula_x;


class formula_describer
{
    private $langs=[];
    private $office_page;
    public static function create() : formula_describer
    {
        return new formula_describer();
    }

    public function get_office_page(string $lang) : string
    {

        return $lang=='cs'?
            'https://support.office.com/cs-cz/article/if-function-'.$this->office_page:
            'https://support.office.com/en-us/article/if-function-'.$this->office_page;
    }
    public function set_office_page(string $page) : formula_describer
    {
        $this->office_page=$page;
        return $this;
    }
    public function get_localized(string $lang) : formula_describer_lang
    {
        return $this->langs[$lang];
    }
    public function lang(string $lang) : formula_describer_lang
    {
        $l=new formula_describer_lang($this);
        $this->langs[$lang]=$l;
        return $l;
    }
}