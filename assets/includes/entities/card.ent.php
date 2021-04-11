<?php

class Card {
    
    public $cardId;
    public $type;
    public $number;
    public $exp;

    public function __construct($cardId, $type, $number, $exp) {
        $this->cardId = $cardId;
        $this->type = $type;
        $this->number = $number;
        $this->exp = $exp;
    }
}

?>