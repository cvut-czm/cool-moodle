<?php
/**
 * Created by CTU CZM.
 * Author: Jiri Fryc
 * License: GNU GPLv3
 */

namespace local_cool\page;


abstract class abstract_page
{

    private $permex=null;
    protected final function permissionsex() : permissionsex
    {
        if($this->permex==null)
            $this->permex=new permissionsex($this->context());
        return $this->permex;
    }
    public final function require_param(string $name,string $type=PARAM_INT)
    {
        return required_param($name,$type);
    }
    public final function get_param(string $name,$default=null,string $type=PARAM_INT)
    {
        return optional_param($name,$default,$type);
    }
    public final function render_form()
    {

    }
    public final function render_page()
    {

    }

    protected abstract function global_permission(permissionsex $perm);
    protected abstract function context() : \context;
    protected abstract function run();

    public final static function execute()
    {
        $instance=new static();
        $instance->global_permission($instance->permissionsex());
        $instance->run();
    }
}