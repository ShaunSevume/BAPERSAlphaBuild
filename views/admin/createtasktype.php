<?php

// imports
import('entities/tasktype.ent');
import('controllers/tasktype.cont');

// the controller
$ttc = new TaskTypeController($data['db']);

// check if form submit
if (isset($_POST['createTaskSubmit'])) {

    $tt = new TaskType(null, $_POST['name'], $_POST['price'], $_POST['description'], $_POST['location']);
    $ttc->createTaskType($tt);

    if ($ttc->success) {
        echo '<div class="alert alert-success">Task Type successfully created!</div>';
    } else {
        echo $ttc->msg;
    }
}

?>

<form class="bapers-form" method="POST">
    <label>Task Type</label>
    <input class="form-control" type="text" placeholder="Enter Task name" name="name">

    <label>Task Price</label>
    <input class="form-control" type="text" placeholder="Enter Task price without £" name="price">

    <label>Task Description</label>
    <textarea class="form-control" placeholder="Enter Task description" name="description"></textarea>

    <label>Task Location</label>
    <input class="form-control" type="text" placeholder="Enter Task location" name="location">

    <button class="btn btn-primary" type="submit" name="createTaskSubmit">Create Task Type</button>
</form>