<?php

class TaskController extends Controller {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // adds a new task to the database
    public function addTask($jid, $disc, $ttid) {
        $this->db->push("INSERT INTO tasks (job_id, t_discount, tasktype_id) VALUES ($jid, $disc, $ttid)");
        if ($this->db->success) {
            $this->succ();
        } else {
            $this->err($this->db->msg);
        }
    }


    // gets the task
    public function getJobTaskList($jid) {
        $fd = $this->db->pullAll("SELECT t_id, t_status, t_start_time, t_duration, s_name, tt_name, tt_desc, tt_location FROM tasks t LEFT JOIN staff s ON t.staff_id = s.s_id LEFT JOIN tasktypes tt ON t.tasktype_id = tt.tt_id WHERE t.job_id = $jid ORDER BY t_status DESC");
        if ($this->db->success) {
            $r = array();
            foreach ($fd as $f) {
                $tt = new TaskType(null, $f['tt_name'], null, $f['tt_desc'], $f['tt_location']);
                array_push($r, new Task($f['t_id'], $f['t_status'], $f['t_start_time'], $f['t_duration'], null, $tt, $f['s_name']));
            }
            $this->succ();
            return $r;
        } else {
            $this->err($this->db->msg);
        }
    }
    
    // mark job as start
    public function taskStart($tid, $sid) {
        $this->db->push("UPDATE tasks SET t_status = 1, t_start_time = NOW(), staff_id = $sid WHERE t_id = $tid");
        if ($this->db->success) {
            $this->succ();
        } else {
            $this->err($this->db->msg);
        }
    }

    // mark task as complete
    public function taskComplete($tid) {
        $this->db->push("UPDATE tasks SET t_status = 2, t_duration = TIMESTAMPDIFF(MINUTE, t_start_time, NOW()) WHERE t_id = $tid");
        if ($this->db->success) {
            $this->succ();
        } else {
            $this->err($this->db->msg);
        }
    }

}

?>