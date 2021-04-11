<?php

class DBHelper extends Controller {

    public $con;

    public function __construct() {
        
        // connecting to the database
        $dbInfo = APP_DATA['db_info'];
        $this->con = new mysqli($dbInfo['host'], $dbInfo['user'], $dbInfo['pass'], $dbInfo['dbname']);
        if ($this->con->connect_errno) {
            echo '<h1>Failed to connect to the Database, please report to admin.</h1>';
            exit();
        }
    }

    public function pull($q) {
        if ($r = $this->con->query($q)) {
            if ($r->num_rows) {
                $this->succ();
                return $r->fetch_assoc();
            } else {
                $this->err('Could not find what you were looking for.');
            }
        } else {
            $this->err('Query could not be executed.');
        }
    }

    public function pullAll($q) {
        if ($r = $this->con->query($q)) {
            if ($r->num_rows) {
                $this->succ();
                return $r->fetch_all(MYSQLI_ASSOC);
            } else {
                $this->err('Sorry, I could not find what you were looking for. :/');
            }
        } else {
            $this->err('Query could not be executed.');
        }
    }

    public function push($q) {
        if ($this->con->query($q)) {
            $this->succ();
        } else {
            $this->err('Query could not be executed.');
        }
    }

    public function escape($string) {
        return $this->con->real_escape_string($string);
    }

    public function err($msg) {
        parent::err($msg  . $this->con->error);
    }

    public function __destruct() {
        $this->con->close();
    }

}

?>