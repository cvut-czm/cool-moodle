<?php
/**
 * Created by CTU CZM.
 * Author: Jiri Fryc
 * License: GNU GPLv3
 */

namespace local_cool\entity;


use Horde\Socket\Client\Exception;

class config_plugin extends database_entity
{
    const TableName = 'config_plugins';

    protected $plugin;
    protected $name;
    protected $value;

    public static function get_or_create(string $plugin, string $name, $default=null)
    {
        $entity=null;
        try {
            $entity = self::get(['plugin' => $plugin, 'name' => $name]);
        }
        catch (\dml_exception $e)
        {
            $entity=null;
        }
        if($entity==null)
        {
            $entity=new config_plugin();
            $entity->plugin=$plugin;
            $entity->name=$name;
            $entity->value=$default;
            $entity->save();
        }
        return $entity->value;
    }
}