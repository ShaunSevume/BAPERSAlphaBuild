<?php

// imports
include 'config.php';
import('controllers/cont');
import('controllers/db.cont');
import('controllers/user.cont');
import('entities/user.ent');
import('controllers/customer.cont');
import('entities/customer.ent');
import('controllers/job.cont');
import('entities/job.ent');

// content to json
header('Content-Type: application/json');

// stuff
$db = new DBHelper();

// Navigation
if (isset($_GET['type'])) {

    switch ($_GET['type']) {

        case 'searchCustomer':
            if (isset($_GET['s']) && !empty($_GET['s'])) {
                searchCustomers($db, $_GET['s']);
            }
        break;

        case 'verifyOM':
            verifyOM($db);
        break;

        case 'testing':
            test($db);
        break;
    }
}

// verify user
function verifyOM($db) {
    if (isset($_POST['sid']) && isset($_POST['spass']) && !empty($_POST['sid']) && !empty($_POST['spass'])) {
        $uc = new UserController($db);
        $uc->verifyUser($_POST['sid'], $_POST['spass']);
        if ($uc->success) {
            $u = $uc->getUser($_POST['sid']);
            if ($u->srole == 'om') {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false, 'msg' => 'User does not have sufficient permission.'));
            }
        } else {
            echo json_encode(array('success' => false, 'msg' => $uc->msg));
        }
    } else {
        echo json_encode(array('success' => false, 'msg' => 'Please input your login details.'));
    }
}

// searching customer
function searchCustomers($db, $s) {
    $cc = new CustomerController($db);
    $c = $cc->searchCustomers($s);
    if ($cc->success) {
        echo json_encode($c);
    } else {
        echo 'Something went wrong: ' . $cc->msg;
    }
}

// test function
function test($db) {
    $estTime = date("Y-m-d H:i:s", strtotime('+24 hours'));
    echo json_encode("test");
}

?>