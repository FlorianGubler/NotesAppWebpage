<?php 
class Note{
    public $id;
    public $value;
    public $examName;
    public $FK_subject;
    public $FK_user;
    public $FK_school;
    public $FK_semester;
    public $additionalTag;
    public $schoolName;
    public $semesterTag;
    public $subjectName;

    function __construct($_id, $_value, $_examName, $_FK_subject, $_FK_user, $_FK_school, $_FK_semester, $_additionalTag, $_schoolName, $_semesterTag, $_subjectName){
        $this->id = $_id;
        $this->value = $_value;
        $this->examName = $_examName;
        $this->FK_subject = $_FK_subject;
        $this->FK_user = $_FK_user;
        $this->FK_school = $_FK_school;
        $this->FK_semester = $_FK_semester;
        $this->additionalTag = $_additionalTag;
        $this->schoolName = $_schoolName;
        $this->semesterTag = $_semesterTag;
        $this->subjectName = $_subjectName;
    }
}
?>