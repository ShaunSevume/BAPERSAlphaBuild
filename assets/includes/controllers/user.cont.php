<?php

class UserController extends Controller {

    private $db;

    // getting the db object
    public function __construct(&$db) {
        $this->db = $db;
    }

    // create user
    public function addUser(&$user, $pass) {
        do {
            $user->generateId();
        } while ($this->staffIdExists($user->sid));
        $user->serialiseData($this->db);

        $pass = password_hash($pass, PASSWORD_DEFAULT); // hashing the pass
        $this->db->push("INSERT INTO staff (s_id, s_name, s_password, s_role) VALUES ($user->sid, '$user->sname', '$pass', '$user->srole')");
        if ($this->db->success) {
            $this->succ();
        } else {
            $this->err($this->db->msg);
        }
    }

    // update user
    public function updateUser(&$user, $pass = "") {
        $user->serialiseData($this->db);
        $p = "";
        if (!empty($pass)) {
            $p = "s_password = '" . password_hash($pass, PASSWORD_DEFAULT) . "', ";
        }
        $this->db->push("UPDATE staff SET s_name = '$user->sname', $p s_role = '$user->srole' WHERE s_id = $user->sid");
        if ($this->db->success) {
            $this->succ();
        } else {
            $this->err($this->db->msg);
        }
    }
    
    // check if job exists
    public function staffIdExists($id) {
        $this->db->pull("SELECT s_id FROM staff WHERE s_id = $id");
        if ($this->db->success) {
            return true;
        } else {
            return false;
        }
    }

    // returns a user entity
    public function getUser($id) {
        $f = $this->db->pull("SELECT s_id, s_name, s_role FROM staff WHERE s_id = $id");
        if ($this->db->success) {
            $this->succ();
            return new User($f['s_id'], $f['s_name'], $f['s_role']);
        } else {
            $this->err($this->db->msg);
        }
    }

    // returns all users
    public function getUserList() {
        $fd = $this->db->pullAll("SELECT s_id, s_name FROM staff");
        if ($this->db->success) {
            $r = array();
            foreach ($fd as $f) {
                array_push($r, new User($f['s_id'], $f['s_name'], null));
            }
            $this->succ();
            return $r;
        } else {
            $this->err($this->db->msg);
        }
    }

    // creating session
    public function login($id, $pass) {
        $this->verifyUser($id, $pass);
        if ($this->success) {
            $_SESSION['sid'] = $id;
        }
    }

    // verify user
    public function verifyUser($id, $pass) {
        $f = $this->db->pull("SELECT s_id, s_password FROM staff WHERE s_id = $id");
        if ($this->db->success) {
            if (password_verify($pass, $f['s_password'])) {
                $this->succ();
            } else {
                $this->err('Your password is incorrect.');
            }
        } else {
            $this->err('Could not find Staff.');
        }
    }

    // loggedin
    public function loggedin() {
        return isset($_SESSION['sid']);
    }

}

?>