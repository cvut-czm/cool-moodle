<?php
/**
 * Created by CTU CZM.
 * Author: Jiri Fryc
 * License: GNU GPLv3
 */

namespace local_cool;


class course_category_api
{
    private const TABLE='course_categories';
    public static function get_or_create_category(string $name,?string $idnumber=null,?int $parent=null) : course_category_api
    {
        $data=new \stdClass();
        $data->name=$name;
        if($idnumber!==null)
            $data->idnumber=$idnumber;
        if($parent!==null)
            $data->parent=$parent;
        \coursecat::create($data);
    }
    public static function get_by_idnumber(string $idnumber) : course_category_api
    {
        global $DB;
        return new course_category_api($DB->get_record(self::TABLE,['idnumber'=>$idnumber]));
    }
    public static function get_by_name(string $name,int $parent=0)
    {
        global $DB;
        return new course_category_api($DB->get_record(self::TABLE,['name'=>$name,'parent'=>$parent]));
    }


    private $data;
    public function __construct($data)
    {
        $this->data=$data;
    }
}