<?php

class User {
    public $sid;
    public $sname;
    public $srole;
    private $roleArray = array('om' => 'Office Manager', 'sm' => 'Shift Manager', 't' => 'Technician', 'r' => 'Receptionist');

    public function __construct($sid, $sname, $srole) {
        $this->sid = $sid;
        $this->sname = $sname;
        $this->srole = $srole;
    }

    // checks if this user has given permission/s
    public function hasPermission($req) {
        if (is_array($req)) {
            foreach ($req as $perm) {
                if ($this->srole == $perm) {
                    return true;
                }
            }
        } else {
            if ($this->srole == $req) {
                return true;
            }
        }
        return false;
    }

    public function roleToString() {
        return $this->roleArray[$this->srole];
    }

    public function serialiseData($db) {
        $this->sname = $db->escape(htmlentities($this->sname));
    }

    public function generateId() {
        $this->sid = rand(1000, 9999);
    }
}

?>