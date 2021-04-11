<?php

class JobController extends Controller {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // adds a new job to the database
    public function addJob(&$job) {
        do {
            $job->generateId();
        } while ($this->jobIdExists($job->jid));
        $job->serialiseData($this->db);
        
        $instruction = (!empty($job->instruction) ? "'".$job->instruction . "'" : 'NULL');
        $this->db->push("INSERT INTO jobs (j_id, j_est_time, j_special_instructions, j_price, customer_no) VALUES ($job->jid, '$job->estTime', $instruction, $job->price, $job->customer)");
        if ($this->db->success) {
            $this->succ();
        } else {
            $this->err($this->db->msg);
        }
    }
    
    // check if job exists
    public function jobIdExists($id) {
        $this->db->pull("SELECT j_id FROM jobs WHERE j_id = $id");
        if ($this->db->success) {
            return true;
        } else {
            return false;
        }
    }

    // getting jobs
    public function getJobRaw($id) {
        $f = $this->db->pull("SELECT j.*, c.c_type AS priority FROM jobs j LEFT JOIN customers c ON j.customer_no = c.c_no WHERE j_id = $id");
        if ($this->db->success) {
            $this->succ();
            return new Job($f['j_id'], $f['j_status'], $f['j_est_time'], $f['j_completion_time'], $f['j_special_instructions'], $f['j_price'], $f['j_paid'], $f['customer_no'], null, null);
        } else {
            $this->err($this->db->msg);
        }
    }

    // getting jobs
    public function getJob($id) {
        $j = $this->getJobRaw($id);
        if ($this->success) {
            $this->succ();
            $tc = new TaskController($this->db);
            $tasks = $tc->getJobTaskList($id);
            $j->tasks = $tasks;
            return $j;
        } else {
            $this->err($this->db->msg);
        }
    }

    // return job tasks amount
    public function getJobTaskAmount($id) {
        $f = $this->db->pull("SELECT COUNT(t_id) AS taskAmount FROM tasks WHERE job_id = $id");
        if ($this->db->success) {
            return $f['taskAmount'];
        } else {
            $this->err($this->db->msg);
        }
    }

    // returns job list
    public function getJobList($page, $limit, $type = 'pending') {
        $off = ($page - 1) * $limit;
        $sa = 'j_status = ' . ($type == 'pending' ? '0 OR j_status = 1' : '2');
        $fc = $this->db->pull("SELECT COUNT(j_id) AS total FROM jobs WHERE $sa");
        
        $r['total'] = $fc;
        $r['totalPages'] = ceil($fc['total'] / $limit);
        $r['jobs'] = array();

        $fd = $this->db->pullAll("SELECT j.j_id, j.j_status, j.j_est_time, j.j_paid, c.c_type as priority FROM jobs j LEFT JOIN customers c ON j.customer_no = c.c_no WHERE $sa ORDER BY " . ($type == 'pending' ? 'j_est_time, priority DESC' : 'j_paid, priority ASC') . " LIMIT $limit OFFSET $off");
        if ($this->db->success) {
            foreach ($fd as $f) {
                array_push($r['jobs'], new Job($f['j_id'], $f['j_status'], $f['j_est_time'], null, null, null, $f['j_paid'], null, null, null));
            }
            $this->succ();
            return $r;
        } else {
            $this->err($this->db->msg);
        }
    }

    // get raw payment job list
    public function getPaymentJobList() {

        $beginDate = date("Y-m-d 00:00:00", strtotime("first day of previous month"));
        $endDate = date("Y-m-d 23:59:59", strtotime("last day of previous month"));

        $bq = "SELECT j.j_id AS jid, j.j_price AS price, c.c_no AS cid, c.c_type AS priority, c.c_name AS cname, j.j_completion_time AS completed FROM jobs j LEFT JOIN customers c ON j.customer_no = c.c_no WHERE j.j_status = 2 AND j.j_paid = 0 AND ";

        // 1. gets the standard customer jobs / 2. gets valued customer jobs from last month
        $fd = $this->db->pullAll($bq . "c.c_type = 0 ORDER BY j.j_est_time");
        if (!$this->db->success) {
            $fd = array();
        }
        $fd2 = $this->db->pullAll($bq . "c.c_type = 1 AND j.j_est_time BETWEEN '$beginDate' AND '$endDate' ORDER BY c.c_no, j.j_est_time");
        if (!$this->db->success) {
            $fd2 = array();
        }

        $this->succ();
        return array_merge($fd, $fd2);

    }
    

    // job start
    public function updateJob($jid, $status) {
        if ($status == 2) {
            $cTime = ", j_completion_time = NOW() ";
        } else {
            $cTime = "";
        }
        $this->db->push("UPDATE jobs SET j_status = $status $cTime WHERE j_id = $jid");
        if ($this->db->success) {
            $this->succ();
        } else {
            $this->err($this->db->msg);
        }
    }

    // update paid
    public function updatePaid($jid, $pid) {
        $this->db->push("UPDATE jobs SET j_paid = 1, payment_id = $pid WHERE j_id = $jid");
        if ($this->db->success) {
            $this->succ();
        } else {
            $this->err($this->db->msg);
        }
    }

}

?>