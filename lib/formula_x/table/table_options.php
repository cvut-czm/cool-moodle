<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 31/05/2018
 * Time: 13:43
 */

namespace formula_x\table;


class table_options
{
    private $options=[
        'show_header'=>true,
        'count_rows'=>false,
    ];

    /**
     * Show/hide table header
     *
     * @param bool $show should be header visible.
     * @return $this for fluent API
    */
    public function show_header(bool $show=true) : table_options
    {
        $this->options['show_header']=$show;
        return $this;
    }
    /**
     * Is table header visible?
     *
     * @return bool visibility of the header.
    */
    public function header_visible() : bool
    {
        return $this->options['show_header'];
    }
}