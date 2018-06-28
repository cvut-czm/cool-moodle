<?php
/**
 * Created by CTU CZM.
 * Author: Jiri Fryc
 * License: GNU GPLv3
 */

namespace local_cool\entity;


use Horde\Socket\Client\Exception;

class user extends database_entity
{
    const TableName = 'user';

    protected $auth;
    protected $confirmed;
    protected $policyagreed;
    protected $deleted;
    protected $firstname;
    protected $lastname;
    protected $username;

    public function get_username() : string
    {
        return $this->username;
    }
    public function get_fullname() : string
    {
        return $this->firstname.' '.$this->lastname;
    }

    private static $current=null;
    private static $current_set=false;
    public static function current_user() : user
    {
        global $USER;
        if(!self::$current_set) {
            try {
                self::$current = user::get(['id' => $USER->id]);
            } catch (\dml_exception $e)
            {
                self::$current=null;
            }
            self::$current_set=true;
        }
        return self::$current;
    }

}