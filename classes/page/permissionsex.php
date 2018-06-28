<?php
/**
 * Created by CTU CZM.
 * Author: Jiri Fryc
 * License: GNU GPLv3
 */

namespace local_cool\page;


use local_cool\entity\cohort;
use local_cool\entity\user;

class permissionsex
{
    private $_context=null;
    public function __construct(\context $context)
    {
        $this->_context=$context;
    }

    public function any(bool ...$permissions)
    {

    }
    public function all(bool ...$permissions)
    {

    }
    /**
     * @return permissionsex
     * @throws access_denied_exception
    */
    public function require_logged() : permissionsex
    {
        if(isloggedin())
            throw new access_denied_exception();
        return $this;
    }

    /**
     * @return permissionsex
     * @throws access_denied_exception
    */
    public function require_cohort_member(cohort $cohort) : permissionsex
    {
        if(!$cohort->is_member(user::current_user()))
            throw new access_denied_exception();
        return $this;
    }

    public function require_capability(string $capability)
    {
        if(!has_capability($capability,$this->_context))
            throw new access_denied_exception();
        return $this;
    }
}