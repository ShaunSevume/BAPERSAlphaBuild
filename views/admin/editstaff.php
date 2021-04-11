<?php

$uc = new UserController($data['db']);
$user = $uc->getUser($data['router']->args);

if ($uc->success) {

// ifupdate is called
if (isset($_POST['editStaffSubmit'])) {

    $user->sname = $_POST['sname'];
    $user->srole = $_POST['role'];
    $uc->updateUser($user, $_POST['password']);

    if ($uc->success) {
        echo '<div class="alert alert-success">Staff updated successfully!</div>';
    } else {
        echo $uc->msg;
    }

}

?>

<form class="bapers-form" method="POST">

<label>Staff Id</label>
<input class="form-control" type="text" value="<?php echo $user->sid; ?>" disabled>

<label>Staff Name</label>
<input class="form-control" type="text" value="<?php echo $user->sname; ?>" placeholder="Enter Staff name" name="sname">

<label>Staff Role</label>
<select class="form-control" name="role">
    <option value="om" <?php echo ($user->srole == 'om' ? 'selected' : ''); ?>>Office Manager</option>
    <option value="sm" <?php echo ($user->srole == 'sm' ? 'selected' : ''); ?>>Shift Manager</option>
    <option value="r" <?php echo ($user->srole == 'r' ? 'selected' : ''); ?>>Receptionist</option>
    <option value="t" <?php echo ($user->srole == 't' ? 'selected' : ''); ?>>Technician</option>
</select>

<hr>

<label>Staff Password (optional)</label>
<input class="form-control" type="password" placeholder="Type in the password if you want to change" name="password">

<button class="btn btn-primary" type="submit" name="editStaffSubmit">Update Staff</button>

</form>

<?php
} else {
    echo $uc->msg;
}
?>