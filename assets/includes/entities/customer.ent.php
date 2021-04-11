<?php

class Customer {

    public $cid;
    public $type;
    public $name;
    public $cname;
    public $address;
    public $phone;
    public $wallet;
    public $discountType;
    public $discount;

    public function __construct($cid, $type, $name, $cname, $address, $phone, $wallet, $discountType, $discount) {
        $this->cid = $cid;
        $this->type = $type;
        $this->name = $name;
        $this->cname = $cname;
        $this->address = $address;
        $this->phone = $phone;
        $this->wallet = $wallet;
        $this->discountType = $discountType;
        $this->discount = $discount;
        if ($this->discountType == 3) {
            $this->discount = str_replace(' ', '', $discount);
        }
    }

    public function parseBounds() {
        $a = explode(';', $this->discount);
        foreach ($a as &$b) {
            $e = explode('>', $b);
            $b = array('min' => $e[0], 'pct' => $e[1]);
        }
        return $a;
    }

    public function serialiseData($db) {
        $this->name = $db->escape($this->name);
        $this->cname = $db->escape($this->cname);
        $this->address = $db->escape($this->address);
        $this->discount = $db->escape($this->discount);
    }

    public function generateId() {
        $this->cid = rand(1000, 9999);
    }
}

?>