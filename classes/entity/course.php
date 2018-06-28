<?php
/**
 * Created by CTU CZM.
 * Author: Jiri Fryc
 * License: GNU GPLv3
 */

namespace local_cool\entity;


class course extends database_entity
{
    /**
     * Name of database table for entity.
     */
    const TableName = 'course';

    protected $category;
    protected $sortorder;
    protected $fullname;
    protected $shortname;
    protected $idnumber;
    protected $summary;
    protected $summaryformat;
    protected $format;
    protected $showgrades;
    protected $newsitems;
    protected $startdate;
    protected $enddate;
    protected $marker;
    protected $maxbytes;
    protected $legacyfiles;
    protected $showreports;
    protected $visible;
    protected $visibleold;
    protected $groupmode;
    protected $groupmodeforce;
    protected $defaultgroupingid;
    protected $lang;
    protected $calendartype;
    protected $theme;
    protected $timecreated;
    protected $timemodified;
    protected $requested;
    protected $enablecompletion;
    protected $completionnotify;
    protected $cacherev;

    public function get_time_created() : int
    {
        return (int)$this->timecreated;
    }
    public function set_shortname(string $new_name) : course
    {
        $this->shortname=$new_name;
        return $this;
    }
    public function get_shortname() : string
    {
        return $this->shortname;
    }
    public function set_fullname(string $new_name) : course
    {
        $this->fullname=$new_name;
        return $this;
    }
    public function get_fullname() : string
    {
        return $this->fullname;
    }
    public function set_visibility(bool $visibility) : course
    {
        $this->visible=$visibility?'1':'0';
        $this->visibleold=$this->visible;
        return $this;
    }
    public function get_visibility() : bool
    {
        return $this->visible=='1';
    }


    public function set_category_id(int $category_id) : course
    {
        $this->category=$category_id;
        return $this;
    }
    public function set_category(course_category $category) : course
    {
        $this->category=$category->get_id();
        return $this;
    }
    public function get_category_id() : int
    {
        return (int)$this->category;
    }
    public function get_category() : course_category
    {
        return course_category::get($this->category);
    }
    public function set_idnumber(string $idnumber) : course
    {
        $this->idnumber=$idnumber;
        return $this;
    }
    public function get_idnumber() : string
    {
        return $this->idnumber;
    }

}