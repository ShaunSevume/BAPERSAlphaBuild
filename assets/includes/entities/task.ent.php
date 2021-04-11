<?php

class Task {

    public $tid;
    public $status;
    public $startTime;
    public $duration;
    public $discount;
    public $taskType; // object of tasktype
    public $staff; // name of staff

    public function __construct($tid, $status, $startTime, $duration, $discount, $taskType, $staff) {
        $this->tid = $tid;
        $this->status = $status;
        $this->startTime = $startTime;
        $this->duration = $duration;
        $this->discount = $discount;
        $this->taskType = $taskType;
        $this->staff = $staff;
    }

}

?>