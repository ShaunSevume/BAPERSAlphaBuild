<?php

// the controller
$uc = new UserController($data['db']);

// check if form submit
if (isset($_POST['createStaffSubmit'])) {

    $user = new User(null, $_POST['sname'], $_POST['role']);
    $uc->addUser($user, $_POST['password']);

    if ($uc->success) {
        echo '<div class="alert alert-success">Staff successfully created with the id: ' . $user->sid . '</div>';
    } else {
        echo $uc->msg;
    }
}

?>

<form class="bapers-form" method="POST">
    <label>Staff Name</label>
    <input class="form-control" type="text" placeholder="Enter Staff name" name="sname">

    <label>Staff Password</label>
    <input class="form-control" type="password" placeholder="Enter Staff password" name="password">

    <label>Staff Role</label>
    <select class="form-control" name="role">
        <option value="om">Office Manager</option>
        <option value="sm">Shift Manager</option>
        <option value="r">Receptionist</option>
        <option value="t">Technician</option>
    </select>

    <button class="btn btn-primary" type="submit" name="createStaffSubmit">Create Staff</button>
</form>