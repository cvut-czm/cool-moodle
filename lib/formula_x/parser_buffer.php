<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 09.03.2018
 * Time: 6:33
 */

namespace formula_x;


class parser_buffer
{
    private $buffer='';
    public function reset()
    {
        $this->buffer='';
    }
    public function is_alphanumerical()
    {
        return ctype_alnum($this->buffer);
    }
    public function is_numerical()
    {
        return ctype_digit($this->buffer);
    }
    public function is_alpha()
    {
        return ctype_alpha($this->buffer);
    }
    public function add_char($char)
    {
        $this->buffer.=$char;
    }
}