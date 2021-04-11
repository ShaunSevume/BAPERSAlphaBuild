<?php

// import
import('controllers/job.cont');
import('entities/job.ent');

// functionality
if (isset($_POST['completed'])) {
    $vjType = 'completed';
} else {
    $vjType = 'pending';
}

// the list
$jc = new JobController($data['db']);
if (!isset($_GET['p']) || $_GET['p'] < 1) {
    $jPage = 1;
} else {
    $jPage = $_GET['p'];
}
$jList = $jc->getJobList($jPage, APP_DATA['list_pagination_limit'], $vjType);
?>

<ul class="nav bp-pill-nav nav-pills nav-fill bg-dark">
    <li class="nav-item">
        <form method="post" action="<?php echo WEB_CONFIG['home']. 'index.php?v=jobs&p=0'; ?>">
            <input type="text" name="pending">
            <button class="nav-link <?php echo $vjType == 'pending' ? 'active' : ''; ?>" href="#">Active/Pending Jobs</button>
        </form>
    </li>
    <li class="nav-item">
        <form method="post" action="<?php echo WEB_CONFIG['home']. 'index.php?v=jobs&p=0'; ?>">
            <input type="text" name="completed">
            <button class="nav-link <?php echo $vjType == 'completed' ? 'active' : ''; ?>" href="#">Completed Jobs</button>
        </form>
    </li>
</ul>

<?php
if ($jc->success) {

    // check if ative/pending list or completed
    if ($vjType == 'completed') {
        
        // iterate the paid
        foreach($jList['jobs'] as $j) {
        ?>

        <div class="card bp-lv-card text-white bg-success">
            <div class="card-header">
                <i class="feather icon-check-circle"></i>
                Job:
                <?php
                echo $j->jid . ' <i style="margin-left:5px;margin-right:5px;" class="feather icon-arrow-right"></i> ' . $j->statusToString();
                ?>
                
                <?php 
                if ($data['user']->hasPermission(array('om', 'sm', 'r'))) {
                    if ($data['user']->hasPermission('om')) {
                    ?>
                    <div class="lv-close-cue lv-drop">
                        <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="feather icon-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo WEB_CONFIG['home'] .'index.php?v=reports&s=genreport&type=invoice&jid=' . $j->jid; ?>" class="dropdown-item">View Invoice</a>
                                <?php echo !$j->paid ? '<a href="'. WEB_CONFIG['home'] .'index.php?v=payments&s=recordpayment&jid='. $j->jid .'" class="dropdown-item">Record Payment</a>' : ''; ?>
                            </li>
                        </ul>
                    </div>

                    <?php
                    } else if ($data['user']->hasPermission('sm', 'r')) {
                        echo '<a href="'. WEB_CONFIG['home'] .'index.php?v=payments&s=recordpayment&jid='. $j->jid .'" class="btn btn-success btn-sm lv-close-cue">Record Payment</a>';
                    }

                    if (!$j->paid) {
                        echo '<span style="margin-right:5px;" class="lv-close-cue lv-alert">';
                        echo '<i style="margin-right:5px;" class="feather icon-flag"></i>Unpaid';
                        echo '</span>';
                    }
                }
                ?>
            </div>
        </div>

        <?php
        }

    } else {
    
        // iterating through active/pending list
        foreach($jList['jobs'] as $j) {
        ?>
        <a href="<?php echo WEB_CONFIG['home'] . 'index.php?v=jobs&s=viewjob&jid=' . $j->jid; ?>" class="card bp-lv-card text-white bg-<?php if ($j->status == 0) { echo 'danger'; } else if ($j->status == 1) { echo 'warning'; } else {echo 'success';} ?>">
            <div class="card-header">
                <i class="feather icon-<?php echo $j->status ? 'alert-circle' : 'alert-octagon'; ?>"></i>
                Job:
                <?php
                echo $j->jid . ' <i style="margin-left:5px;margin-right:5px;" class="feather icon-arrow-right"></i> ' . $j->statusToString();

                $estTime = date_create($j->estTime);
                $timeRemaining = $estTime->getTimestamp() - time();
                $hrsRemaining = ceil($timeRemaining/3600);

                if ($hrsRemaining <= APP_DATA['job_late_alert_hours']) {
                ?>
                <span class="lv-close-cue lv-alert">
                    <?php echo 'Job is close to deadline <i class="feather icon-chevron-right"></i> less than '. $hrsRemaining .' hours remaining'; ?>
                </span>
                <?php
                } else {
                ?> 
                <span class="lv-close-cue">
                    <?php echo 'Job due in: '. $hrsRemaining . ' hours'; ?>
                </span>
                <?php
                }
                ?>
            </div>
        </a>
        <?php
        }
    }
    
} else {
    echo $jc->msg;
}

if (isset($jList['totalPages']) && $jPage > 1) {
?>
<form method="post" action="<?php echo WEB_CONFIG['home'] . 'index.php?v=jobs&p=' . ($jPage - 1); ?>">
    <input style="display:none;" type="text" name="<?php echo $vjType; ?>">
    <button type="submit" class="btn btn-primary"><i class="feather icon-arrow-left"></i></button>
</form>
<?php
}

if (isset($jList['totalPages']) && $jPage < $jList['totalPages']) {
?>
<form method="post" action="<?php echo WEB_CONFIG['home'] . 'index.php?v=jobs&p=' . ($jPage + 1); ?>">
    <input style="display:none;" type="text" name="<?php echo $vjType; ?>">
    <button type="submit" class="btn btn-primary"><i class="feather icon-arrow-right"></i></button>
</form>
<?php
}

echo '<a href="'. WEB_CONFIG['home'] . 'index.php?v=jobs&s=addjob" class="btn btn-primary"><i style="margin-right:10px;" class="feather icon-plus"></i>Create Job</a>';
?>