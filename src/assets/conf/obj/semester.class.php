<?php 
class Semester{
    public $id;
    public $semesterTag;

    function __construct($_id, $_semesterTag)
    {
        $this->id = $_id;
        $this->semesterTag = $_semesterTag;
    }
}
?>