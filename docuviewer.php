<?php

include 'config.php';

// imports
import('controllers/cont');
import('controllers/server.cont');

if (isset($_GET['filepath']) && isset($_GET['filename'])) {

    $sc = new ServerController();
    $r = $sc->getTempReportFile($_GET['filepath']);

    if ($sc->success) {
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $_GET['filename'] . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        
        @readfile($r);
        $sc->closeTemp();
    }
}

?>