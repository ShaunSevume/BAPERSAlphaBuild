<?php

// import
import('entities/tasktype.ent');
import('controllers/tasktype.cont');

// staff
$uc = new UserController($data['db']);
$ul = $uc->getUserList();

// task types
$ttc = new TaskTypeController($data['db']);
$ttl = $ttc->getTaskTypeList();

if ($uc->success) {
?>

<div class="row">
    <div class="col">
        <h5>Staff</h5><br>
        <?php
        foreach ($ul as $user) {
        ?>

        <a href="<?php echo WEB_CONFIG['home'] . 'index.php?v=admin&s=editstaff&sid=' . $user->sid; ?>" class="card bp-lv-card text-white bg-secondary">
            <div class="card-header">
                <i class="feather icon-user-check"></i>
                <?php echo $user->sid . ' | ' . $user->sname; ?>
            </div>
        </a>

        <?php
        }
        ?>
        <a href="<?php echo WEB_CONFIG['home'] . 'index.php?v=admin&s=createstaff'; ?>" class="btn btn-primary"><i style="margin-right:10px;" class="feather icon-plus"></i>Create Staff</a>
    </div>

<?php
}

if ($ttc->success) {
?>

    <div class="col">
        <h5>Task Types</h5><br>
        <?php
        foreach ($ttl as $tt) {
        ?>

        <a href="<?php echo WEB_CONFIG['home'] . 'index.php?v=admin&s=edittasktype&ttid=' . $tt->ttid; ?>" class="card bp-lv-card text-white bg-secondary">
            <div class="card-header">
                <i class="feather icon-disc"></i>
                <?php echo $tt->name; ?>
            </div>
        </a>

        <?php
        }
        ?>
        <a href="<?php echo WEB_CONFIG['home'] . 'index.php?v=admin&s=createtasktype'; ?>" class="btn btn-primary"><i style="margin-right:10px;" class="feather icon-plus"></i>Create Task Type</a>
    </div>

<?php
}
?>

</div>
