<?php
/**
 * Created by CTU CZM.
 * Author: Jiri Fryc
 * License: GNU GPLv3
 */

namespace local_cool\entity;


class course_category extends database_entity
{
    const TableName = 'course_categories';

    protected $name;
    protected $idnumber;
    protected $description;
    protected $descriptionformat;
    protected $parent;
    protected $sortorder;
    protected $coursecount;
    protected $visible;
    protected $visibleold;
    protected $timemodified;
    protected $depth;
    protected $path;
    protected $theme;

    public function get_courses() : array
    {
        return course::get_all(['category'=>$this->id]);
    }

}