<?php
class School
{
    public $id;
    public $schoolName;

    function __construct($_id, $_schoolname)
    {
        $this->id = $_id;
        $this->schoolName = $_schoolname;
    }
}
