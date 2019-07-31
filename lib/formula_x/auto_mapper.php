<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 11.03.2018
 * Time: 18:37
 */

namespace formula_x;

/**
 * auto_mapper detects all functions inside functions folder.
 *
 * @author Jiří Fryč <jirifryc.cz@jirifryc.cz>
 * @copyright GPLv3
*/
class auto_mapper
{

    /** @var array $mapped_functions Array that holds already mapped functions. */
    private static $mapped_functions=null;

    /**
     * Tries to find function in all mapped categories and return it.
     *
     * @param string $name Name of the function
     * @return  formula_function|null
    */
    public static function get_function(string $name) : formula_function
    {
        foreach(self::map_all() as $category=>$group)
            if(in_array(strtoupper($name),$group)) {
                $fnc_name = 'formula_x\\functions\\'.$category.'\\function_' . strtolower($name);
                $fnc=new $fnc_name();
                return $fnc;
            }
        return null;
    }

    public static function set_custom_mapping($map)
    {
        self::$mapped_functions=$map;
    }
    /**
     * For mapping all functions inside functions folder.
     *
     * It is run only once, after that, cached array is returned.
     *
     * @return array
    */
    public static function map_all() : array
    {
        if(self::$mapped_functions==null) {
            $path = dirname(__FILE__) . '/functions';
            $paths = glob($path . '/*', GLOB_ONLYDIR);
            $map = [];
            foreach ($paths as $dir) {
                $files = scandir($dir);
                $dirname = ucfirst(basename($dir));
                $map[$dirname] = [];
                foreach ($files as $file) {
                    if (!is_file($dir . '/' . $file))
                        continue;
                    $filename = substr($file, strpos($file, '_') + 1, -4);
                    $map[$dirname][] = strtoupper($filename);
                }
            }
            self::$mapped_functions=$map;
        }
        return self::$mapped_functions;
    }
}