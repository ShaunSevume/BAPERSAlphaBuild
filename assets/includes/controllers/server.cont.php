<?php

class ServerController extends Controller {

    public $con;
    public $socket;
    private $temp;

    // connect to ftp
    public function __construct() {

        // connect to ftp
        $ftpInfo = APP_DATA['ftp_info'];
        $this->con = ftp_connect($ftpInfo['host']) or die("Could not connect to ftp server.");
        ftp_login($this->con, $ftpInfo['user'], $ftpInfo['pass']);

        // connect to bapers server
        $this->socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
        socket_connect($this->socket, APP_DATA['bapers_info']['host'], APP_DATA['bapers_info']['port']) or die("Could not connect toserver\n");
    }

    // generate invoice
    public function generateInvoice($jnr) {
        $this->sendServerCommand("genreport invoice $jnr");
        $this->succ();
    }

    // generate invoice
    public function generateIndPerf($s, $e) {
        $this->sendServerCommand("genreport individualPerformance $s $e");
        $this->succ();
    }

    // generate jobsheet
    public function generateJobSheet($cnr) {
        $this->sendServerCommand("genreport jobsheet $cnr");
        $this->succ();
    }

    // database backup
    public function dbbackup() {
        $this->sendServerCommand("db backup");
        $this->succ();
    }

    // database backup
    public function dbrestore($fname) {
        $this->sendServerCommand("db restore $fname");
        $this->succ();
    }


    // send command to server
    private function sendServerCommand($message) {
        socket_write($this->socket, $message, strlen($message)) or die("Could not send data to server\n");
    }

    // get temp report file
    public function getTempReportFile($filePath) {

        $this->temp = tmpfile();
        $fileMetaDatas = stream_get_meta_data($this->temp);
        $tmpFileUri = $fileMetaDatas['uri'];
        
        if (ftp_fget($this->con, $this->temp, $filePath, FTP_ASCII, 0)) {
            $this->succ();
            return $tmpFileUri;
        } else {
            echo "There was a problem while trying to get the report.";
        }

    }
    
    // remove pdf from filename
    public function rmLast3($filePath) {
        return substr($filePath, 0, strlen($filePath) - 4);
    }

    // close temporary file
    public function closeTemp() {
        fclose($this->temp);
    }

    // get content list of a folder $dir
    public function getFolderContentList($dir) {
        $list = ftp_mlsd($this->con, $dir);
        $r['folders'] = array();
        $r['files'] = array();
        foreach ($list as $i) {
            $i['path'] = $dir.$i['name']; 
            if ($i['type'] == 'dir') {
                array_push($r['folders'], $i);
            } else {
                $i['name'] = $this->rmLast3($i['name']);
                array_push($r['files'], $i);
            }
        }
        $this->succ();
        return array_merge($r['folders'], $r['files']);
    }

    public function __destruct() {
        ftp_close($this->con);
    }

}

?>