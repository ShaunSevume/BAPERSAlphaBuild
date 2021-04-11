<?php

// imports
import('controllers/task.cont');
import('entities/task.ent');
import('controllers/tasktype.cont');
import('entities/tasktype.ent');
import('controllers/job.cont');
import('entities/job.ent');
import('controllers/customer.cont');
import('entities/customer.ent');

// get the taskstypes
$tc = new TaskController($data['db']);
$ttc = new TaskTypeController($data['db']);
$tt = $ttc->getTaskTypeList();

if ($ttc->success) {

// form submission
if (isset($_POST['createJobSubmit']) && !empty($_POST['tasks'])) {

    // calculations
    $cid = (int)$_POST['customerId'];
    
    if ($_POST['urgency'] == 'normal') {
        $estTime = date("Y-m-d H:i:s", strtotime('+24 hours'));
    } else if ($_POST['urgency'] == 'urgent') {
        $estTime = date("Y-m-d H:i:s", strtotime('+6 hours'));
    } else if ($_POST['urgency'] == 'superurgent') {
        $estTime = date("Y-m-d H:i:s", strtotime('+3 hours'));
    } else if ($_POST['urgency'] == 'express') {
        $hrs = $_POST['expressHours'];
        $mins = $_POST['expressMinutes'];
        $estTime = date("Y-m-d H:i:s", strtotime("+ $hrs hours $mins minutes"));
    }

    // create job entity
    $job = new Job(null, null, $estTime, null, $_POST['instructions'], (float)$_POST['totalJobPrice'], null, $cid, null, null);

    // adding the job to the db
    $jc = new JobController($data['db']);
    $jc->addJob($job);

    // create the tasks entities
    foreach ($_POST['tasks'] as $ttid) {
        $tc->addTask($job->jid, (float)$_POST["taskDiscount$ttid"], $ttid);
    }

    $cc = new CustomerController($data['db']);
    $cc->updateWallet($cid, (float)$_POST['customerWallet']);
    
    if ($jc->success && $tc->success && $cc->success) {
        echo '<div class="alert alert-success">Job added successfully with the ID: '. $job->jid .'!</div>';
    } else {
        echo '<div class="alert alert-danger">' . $jc->msg . $tc->msg . $cc->msg . '</div>';
    }

}
?>

<form class="bapers-form" method="POST" id="addjob_form">
    <input style="display:none;" type="text" name="customerId" value="">
    <input style="display:none;" type="text" name="totalJobPrice" value="">
    <input style="display:none;" type="text" name="customerWallet" value="">

    <label>Customer</label>
    <div class="input-group">
        <input id="customerModalTrigger" class="form-control" type="text" placeholder="Select Customer">
        <a class="btn btn-primary" href="<?php echo WEB_CONFIG['home']; ?>?v=customers&s=createcustomer">Create Customer</a>
    </div>

    <label style="margin-top:15px;">Tasks</label>
    <table class="table table-borderless">
    <?php
    foreach($tt as $ttp) {
    ?>
    <tr>
        <td style="width:80px;padding-right:10px;padding-bottom:5px;display:none;" class="variableTaskDiscount">
            <input class="form-control" type="number" placeholder="%"  name="taskDiscount<?php echo $ttp->ttid; ?>">
        </td>
        <td>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" price="<?php echo $ttp->price; ?>" value="<?php echo $ttp->ttid; ?>" id="taskDiscount<?php echo $ttp->ttid; ?>" name="tasks[]">
                <label class="form-check-label" for="taskDiscount<?php echo $ttp->ttid; ?>">
                    <?php echo $ttp->desc != null ?  "$ttp->name | $ttp->desc" : $ttp->name; ?>
                </label>
            </div>
        </td>
        <td>
            <span class="taskPrice"><?php echo '£' . $ttp->price; ?></span>
            <span class="totalTaskPrice"></span>
        </td>
    </tr>
    <?php
    }
    ?>
    <tr>
        <td>
            <div style="margin-top:15px;padding-top:15px;border-top:1px solid #eee;">SubTotal</div>
            <div style="margin-top:5px;">Total</div>
        </td>
        <td style="display:none;" class="variableTaskDiscount"></td>
        <td>
            <div style="margin-top:15px;padding-top:15px;border-top:1px solid #eee;" id="jSubTotal">£0</div>
            <div style="margin-top:5px;" id="jTotal">£0</div>
        </td>
    </tr>
    </table>

    <label style="margin-top:15px;">Level of Urgency</label>
    <div style="margin-bottom:15px;" class="input-group">
        <select id="urgencySelect" class="form-control" name="urgency">
            <option value="normal">Normal | 24 hours</option>
            <option value="urgent">Urgent | 6 hours</option>
            <option value="superurgent">Super Urgent | 3 hours</option>
            <option value="express">Express</option>
        </select>
        
        <input style="display:none;" class="form-control expressTime" type="number" placeholder="Hours" name="expressHours">
        <input style="display:none;" class="form-control expressTime" type="number" placeholder="Minutes" name="expressMinutes">
    </div>

    <label style="display:none;" class="surcharge">Surcharge</label>
    <input style="display:none;" type="number" class="form-control surcharge" placeholder="%" id="surchargeAmount" value="0">

    <label>Special Instruction (optional)</label>
    <textarea class="form-control" type="text" name="instructions"></textarea>
    <button class="btn btn-primary" type="submit" name="createJobSubmit">Create Job</button>

</form>

<div id="customerSearchModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Search Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input class="form-control" id="customerSearchBox" type="text" placeholder="Type in a customer name..." autocomplete="off">
                <div style="margin-top:10px;height:500px;" id="customers-list">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</form>

<div id="variableDiscountModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" id="dcApplyMessage">
                    Customer has variable discount, would you like to apply?
                </div>

                <form class="bapers-form" style="display:none;" id="omVerify">
                    <label>Staff ID</label>
                    <input class="form-control" type="text" name="sid">

                    <label>Password</label>
                    <input class="form-control" type="password" name="spass">

                    <button type="submit" style="width:100%;" class="btn btn-primary">Verify</button>
                </form>
            </div>
            <div class="modal-footer">
                <button id="variableYesBtn" type="button" class="btn btn-secondary">Yes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">No</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">var curUserPermission = <?php echo $data['user']->srole == 'om' ? 'true' : 'false'; ?>;</script>
<script src="<?php echo WEB_CONFIG['asf'].'js/addjob.js'; ?>" type="text/javascript"></script>

<?php
} else {
    echo $tc->msg;
}
?>