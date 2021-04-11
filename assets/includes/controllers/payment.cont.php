<?php

class PaymentController extends Controller {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Record payment
    public function recordPayment(&$payment) {
        if ($payment->card != null) {
            $this->addCard($payment->card);
            $cardId = $payment->card->cardId;
        } else {
            $cardId = "NULL";
        }
        
        $this->db->push("INSERT INTO payments (p_type, p_amount, c_no, card_id) VALUES ('$payment->type', $payment->amount, $payment->customer, $cardId)");
        if ($this->db->success) {
            $this->succ();
            $payment->pid = $this->db->con->insert_id;
        } else {
            $this->err($this->db->msg);
            $payment->pid = $this->db->con->insert_id;
        }
    }

    // returns card_id
    public function addCard(&$card) {
        $this->db->push("INSERT INTO cards (card_type, card_4_digit, card_exp) VALUES ('$card->type', $card->number, $card->exp)");
        if ($this->db->success) {
            $this->succ();
            $card->cardId = $this->db->con->insert_id;
        } else {
            $this->err($this->db->msg);
            $card->cardId = 0;
        }
    }

}

?>