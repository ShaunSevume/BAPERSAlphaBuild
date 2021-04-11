<?php

class CustomerController extends Controller {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // function to create a customer
    public function createCustomer(&$customer) {
        do {
            $customer->generateId();
        } while ($this->customerIdExists($customer->cid));
        $customer->serialiseData($this->db);

        if (!empty($customer->cname)) {
            $ccname = "'$customer->cname', ";
        } else {
            $ccname = "NULL, ";
        }
        $this->db->push("INSERT INTO customers (c_no, c_name, c_contact_name, c_address, c_phone) VALUES($customer->cid, '$customer->name', $ccname '$customer->address', '$customer->phone')");
        if ($this->db->success) {
            $this->succ();
        } else {
            $this->err($this->db->msg);
        }
    }

    // function to update customer
    public function updateCustomer(&$customer) {
        $customer->serialiseData($this->db);
        if (empty($customer->cname)) {
            $ccname = '';
        } else {
            $ccname = "c_contact_name = '$customer->cname', ";
        }
        if (empty($customer->discount)) {
            $cds = "NULL";
        } else {
            $cds = "'$customer->discount'";
        }
        $this->db->push("UPDATE customers SET c_type = $customer->type, c_name = '$customer->name', $ccname c_address = '$customer->address', c_phone = '$customer->phone', c_discount_type = $customer->discountType, c_discount = $cds WHERE c_no = $customer->cid");
        if ($this->db->success) {
            $this->succ();
        } else {
            $this->err($this->db->msg);
        }
    }
    

    // function to update customer
    public function updateWallet($cid, $amount) {
        if ($amount > 0) {
            $this->db->push("UPDATE customers SET c_wallet = $amount WHERE c_no = $cid");
            if ($this->db->success) {
                $this->succ();
            } else {
                $this->err($this->db->msg);
            }
        } else {
            $this->succ();
        }
    }
    
    // check if job exists
    public function customerIdExists($id) {
        $this->db->pull("SELECT c_no FROM customers WHERE c_no = $id");
        if ($this->db->success) {
            return true;
        } else {
            return false;
        }
    }

    public function getCustomer($id) {
        $f = $this->db->pull("SELECT * FROM customers WHERE c_no = $id");
        if ($this->db->success) {
            $this->succ();
            return new Customer($f['c_no'], $f['c_type'], $f['c_name'], $f['c_contact_name'], $f['c_address'], $f['c_phone'], $f['c_wallet'], $f['c_discount_type'], $f['c_discount']);
        } else {
            $this->err($this->db->msg);
        }
    }

    public function getCustomerList() {
        $fd = $this->db->pullAll("SELECT c_no, c_name, c_type FROM customers");
        if ($this->db->success) {
            $r = array();
            foreach ($fd as $f) {
                array_push($r, new Customer($f['c_no'], $f['c_type'], $f['c_name'], null, null, null, null, null, null));
            }
            $this->succ();
            return $r;
        } else {
            $this->err($this->db->msg);
        }
    }

    public function searchCustomers($s) {
        $fd = $this->db->pullAll("SELECT * FROM customers WHERE c_name LIKE '$s%'");
        if ($this->db->success) {
            $r = array();
            foreach($fd as $f) {
                $c = new Customer($f['c_no'], $f['c_type'], $f['c_name'], null, null, null, $f['c_wallet'], $f['c_discount_type'], $f['c_discount']);
                if ($c->discountType == 3) {
                    $c->discount = $c->parseBounds();
                }
                array_push($r, $c);
            }
            $this->succ();
            return $r;
        } else {
            $this->err($this->db->msg);
        }
    }
    
}

?>