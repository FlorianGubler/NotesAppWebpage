<?php
class StickyNotes
{
    public $id;
    public $createdate;
    public $title;

    function __construct($_id, $_createdate, $_title)
    {
        $this->id = $_id;
        $this->createdate = $_createdate;
        $this->title = $_title;
    }
}
