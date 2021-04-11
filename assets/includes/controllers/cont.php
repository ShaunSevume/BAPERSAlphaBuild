<?php

abstract class Controller {
    public $success;
    public $msg;

    protected function succ($e = null) {
        $this->success = true;
        $this->msg = $e;
    }

    protected function err($e) {
        $this->success = false;
        if (APP_DATA['debug_mode']) {
            $this->msg = get_called_class() . ' -> ' . $e;
        } else {
            $this->msg = $e;
        }
    }

}

?>