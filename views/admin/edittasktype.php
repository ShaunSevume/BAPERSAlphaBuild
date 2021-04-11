<?php

import('controllers/tasktype.cont');
import('entities/tasktype.ent');

$ttc = new TaskTypeController($data['db']);
$tt = $ttc->getTaskType($data['router']->args);

if ($ttc->success) {

    if (isset($_POST['editTaskSubmit'])) {
        
        $tt = new TaskType($tt->ttid, $_POST['name'], $_POST['price'], $_POST['description'], $_POST['location']);
        $ttc->updateTaskType($tt);

        if ($ttc->success) {
            echo '<div class="alert alert-success">TaskType has been updated.</div>';
        } else {
            echo '<div class="alert alert-danger">'.$ttc->msg.'</div>';
        }
    }
?>

<form class="bapers-form" method="POST">
    <label>Task Type</label>
    <input class="form-control" type="text" placeholder="Enter Task name" value="<?php echo $tt->name; ?>" name="name">

    <label>Task Price</label>
    <input class="form-control" type="text" placeholder="Enter Task price without £" value="<?php echo $tt->price; ?>" name="price">

    <label>Task Description</label>
    <textarea class="form-control" placeholder="Enter Task description" name="description"><?php echo $tt->desc; ?></textarea>

    <label>Task Location</label>
    <input class="form-control" type="text" placeholder="Enter Task location" value="<?php echo $tt->location; ?>" name="location">

    <button class="btn btn-primary" type="submit" name="editTaskSubmit">Update Task Type</button>
</form>

<?php
} else {
    echo $ttc->msg;
}
?>