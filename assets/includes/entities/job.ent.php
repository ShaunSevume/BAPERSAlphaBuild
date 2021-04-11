<?php

class Job {

    public $jid;
    public $status;
    public $estTime;
    public $compTime;
    public $instruction;
    public $price;
    public $paid;
    public $customer; // object of customer
    public $payment; // object of payment
    public $tasks; // list of tasks
    public $statusArray = array('Pending', 'Active', 'Completed');

    public function __construct($jid, $status, $estTime, $compTime, $instruction, $price, $paid, $customer, $paymentId, $tasks) {
        $this->jid = $jid;
        $this->status = $status;
        $this->estTime = $estTime;
        $this->compTime = $compTime;
        $this->instruction = $instruction;
        $this->price = $price;
        $this->paid = $paid;
        $this->customer = $customer;
        $this->paymentId = $paymentId;
        $this->tasks = $tasks;
    }

    public function statusToString() {
        return $this->statusArray[$this->status];
    }

    public function serialiseData($db) {
        $this->instruction = $db->escape(htmlentities($this->instruction));
    }

    public function generateId() {
        $this->jid = rand(1000, 9999);
    }

}

?>