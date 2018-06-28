<?php
/**
 * Created by CTU CZM.
 * Author: Jiri Fryc
 * License: GNU GPLv3
 */

namespace local_cool\entity;


class role extends database_entity
{
    const TableName = 'role';

    protected $name;
    protected $shortname;
    protected $description;
    protected $sortorder;
    protected $archetype;


    public function get_shortname() : string
    {
        return $this->shortname;
    }

    public static function manager() : role
    {
        return self::get(['shortname'=>'manager']);
    }
    public static function coursecreator() : role
    {
        return self::get(['shortname'=>'coursecreator']);
    }
    public static function editingteacher() : role
    {
        return self::get(['shortname'=>'editingteacher']);
    }
    public static function teacher() : role
    {
        return self::get(['shortname'=>'teacher']);
    }
    public static function student() : role
    {
        return self::get(['shortname'=>'student']);
    }
    public static function guest() : role
    {
        return self::get(['shortname'=>'guest']);
    }
    public static function user() : role
    {
        return self::get(['shortname'=>'user']);
    }
    public static function frontpage() : role
    {
        return self::get(['shortname'=>'frontpage']);
    }
}