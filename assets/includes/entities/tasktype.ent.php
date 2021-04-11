<?php

class TaskType {

    public $ttid;
    public $name;
    public $price;
    public $desc;
    public $location;

    public function __construct($ttid, $name, $price, $desc, $location) {
        $this->ttid = $ttid;
        $this->name = $name;
        $this->price = $price;
        $this->desc = $desc;
        $this->location = $location;
    }

    public function serialiseData($db) {
        $this->name = $db->escape($this->name);
        $this->desc = $db->escape($this->desc);
        $this->location = $db->escape($this->location);
    }

}

?>