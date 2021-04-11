<?php

// imports
import("controllers/job.cont");
import("controllers/task.cont");
import("entities/job.ent");
import("entities/task.ent");
import("entities/tasktype.ent");

$jc = new JobController($data['db']);
$tc = new TaskController($data['db']);

// query successful and found
$amountOfTasks = $jc->getJobTaskAmount($data['router']->args);

// functionality for starting and completing job
if (isset($_POST['start'])) {
    $tc->taskStart($_POST['start'], $data['user']->sid);
    if ($_POST['completedTasks'] == 0) {
        $jc->updateJob($data['router']->args, 1);
    }
} else if (isset($_POST['complete'])) {
    $tc->taskComplete($_POST['complete']);
    if ($_POST['completedTasks'] + 1 == $amountOfTasks) {
        $data['router']->changeView('jobs');
        $jc->updateJob($data['router']->args, 2);
    }
} else {
    echo $jc->msg;
}

// query successful and found
$job = $jc->getJob($data['router']->args);
$completedTasks = 0;

if ($jc->success) {

echo '<h3>JOB: ' . $job->jid . '</h3><br>'; // job id

// check if instruction exists
if ($job->instruction) {
?>
<div class="alert alert-info bp-vj-inst">
    <i class="feather icon-info"></i> Special Instructions: <?php echo $job->instruction; ?>
</div>
<?php
}

// task list
foreach($job->tasks as $task) {
    if ($task->status == 2) {
    $completedTasks++;
    ?>
    <div class="card bp-lv-card text-white bg-success">
        <div class="card-header">
        <i class="feather icon-check-circle"></i>
        <?php echo $task->taskType->name . ' | Completed by: ' . $task->staff; ?>    
        </div>
    </div>
    <?php
    } else if ($task->status == 0 || $task->status == 1) {
    ?>
    <div class="card bp-lv-card text-white bg-<?php echo $task->status == 0 ? 'danger' : 'warning'; ?>">
        <div class="card-header">
            <i class="feather icon-alert-<?php echo $task->status == 0 ? 'octagon' : 'circle'; ?>"></i><?php echo $task->taskType->name; echo $task->staff != null ? ' | Started by: ' . $task->staff : ''; ?>
            <?php
            if ($task->status == 0) {
            ?>
            <form method="post"><input name="start" value="<?php echo $task->tid; ?>"><input style="display:none;" type="text" value="<?php echo $completedTasks; ?>" name="completedTasks"><button type="submit" class="btn btn-secondary">Start Task</button></form>
            <?php
            } else if ($task->status == 1) {
            ?>
            <form method="post"><input name="complete" value="<?php echo $task->tid; ?>"><input style="display:none;" type="text" value="<?php echo $completedTasks; ?>" name="completedTasks"><button type="submit" class="btn btn-light">Mark Complete</button></form>
            <?php
            }
            ?>
        </div>
        <div class="card-body <?php echo $task->taskType->desc != null ? 'lv-body-with-desc' : ''; ?>">
            <?php
            echo '<p class="card-text">' . $task->taskType->desc . '</p>';
            ?>
            <div class="location">
                <i class="feather icon-map-pin"></i>
                <?php echo $task->taskType->location; ?>
            </div>
        </div>
    </div>
    <?php
    }
}
?>

<!-- need to fix -->
<div style="display:inline-block;margin-top:30px;" class="alert alert-dark">
    <?php
    $estTime = date_create($job->estTime);
    $timeRemaining = $estTime->getTimestamp() - time();
    echo 'DUE ON ' . date_format($estTime, 'd/m/Y - H:i') . ' | ' . ceil($timeRemaining/3600) . ' hours remaining';
    ?>
</div>

<?php
} else {
    echo $jc->msg;
}
?>