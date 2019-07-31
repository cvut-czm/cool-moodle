<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 08.03.2018
 * Time: 22:31
 */

namespace formula_x;


class driver
{
    private static $lang_base=null;
    private static $lang_formula_abbrev=null;
    public function options() : driver_option
    {

    }
    private static $table=[];
    public static function set_table(&$table)
    {
        self::$table=$table;
    }
    public static function get_variable($id)
    {
        if(isset(self::$table[$id]))
            return self::$table[$id];
        return 0;
    }
    public static function get_string(string $code) : string
    {
        return self::$lang_base[$code];
    }
    public static function set_language(string $lang)
    {
        $string=[];
        include dirname(__FILE__).'/lang/'.$lang.'/base.php';
        foreach ($string as $k=>$v)
            $string[$k]=strtoupper($v);
        self::$lang_base=$string;
        $formula=[];
        include dirname(__FILE__).'/lang/'.$lang.'/formula_abbrev.php';
        foreach ($formula as $k=>$v)
            $formula[$k]=strtoupper($v);
        self::$lang_formula_abbrev=$formula;

    }

    public static function get_function(string $abbrev)
    {

    }
}
driver::set_language('en'); // Static initializer