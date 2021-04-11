<?php

class Payment {
    
    public $pid;
    public $type;
    public $amount;
    public $customer;
    public $card;

    public function __construct($pid, $type, $amount, $customer, $card) {
        $this->pid = $pid;
        $this->type = $type;
        $this->amount = $amount;
        $this->customer = $customer;
        $this->card = $card;
    }
}


?>