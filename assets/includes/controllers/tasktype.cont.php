<?php

class TaskTypeController extends Controller {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // create task type
    public function createTaskType(&$tt) {
        $tt->serialiseData($this->db);
        $this->db->push("INSERT INTO tasktypes (tt_name, tt_price, tt_desc, tt_location) VALUES ('$tt->name', $tt->price, '$tt->desc', '$tt->location')");
        if ($this->db->success) {
            $this->succ();
        } else {
            $this->err($this->db->msg);
        }
    }

    // get tasktype
    public function getTaskType($id) {
        $f = $this->db->pull("SELECT * FROM tasktypes WHERE tt_id = $id");
        if ($this->db->success) {
            $this->succ();
            return new TaskType($f['tt_id'], $f['tt_name'], $f['tt_price'], $f['tt_desc'], $f['tt_location']);
        } else {
            $this->err($this->db->msg);
        }
    }

    // updating task type
    public function updateTaskType(&$tt) {
        $tt->serialiseData($this->db);
        $this->db->push("UPDATE tasktypes SET tt_name = '$tt->name', tt_price = $tt->price, tt_desc = '$tt->desc', tt_location = '$tt->location' WHERE tt_id = $tt->ttid");
        if ($this->db->success) {
            $this->succ();
        } else {
            $this->err($this->db->msg);
        }
    }

    // get all the task
    public function getTaskTypeList() {
        $fd = $this->db->pullAll("SELECT * FROM tasktypes");
        if ($this->db->success) {
            $r = array();
            foreach ($fd as $f) {
                array_push($r, new TaskType($f['tt_id'], $f['tt_name'], $f['tt_price'], $f['tt_desc'], $f['tt_location']));
            }
            $this->succ();
            return $r;
        } else {
            $this->err($this->db->msg);
        }
    }


}

?>