<?php

// imports
import('controllers/job.cont');
import('entities/job.ent');
import('controllers/payment.cont');
import('entities/payment.ent');
import('entities/card.ent');

// gets the raw job
$jc = new JobController($data['db']);

if ($data['router']->args == 'multi') {
    if (!isset($_POST['cid'])) {
        $data['router']->changeView('payments');
    }
} else {
    $job = $jc->getJobRaw($data['router']->args);
}

// on submit
if (isset($_POST['recordPaymentSubmit'])) {

    // records payment
    $pc = new PaymentController($data['db']);
    if ($data['router']->args == 'multi') {
        $payment = new Payment(null, $_POST['type'], $_POST['amount'], $_POST['cid'], null);
    } else {
        $payment = new Payment(null, $_POST['type'], $job->price, $job->customer, null);
    }

    if ($_POST['type']) {
        $payment->card = new Card(null, $_POST['cardType'], $_POST['cardNumber'], $_POST['cardExpiry']);
    }

    // add new payment
    $pc->recordPayment($payment);

    if ($pc->success) {
        
        // if multi payment, update all jobs
        if ($data['router']->args == 'multi') {
            foreach ($_POST['jobs'] as $jid) {
                $jc->updatePaid($jid, $payment->pid);
            }
        } else {
            $jc->updatePaid($job->jid, $payment->pid);
        }

        $data['router']->changeView('payments');
    } else {
        echo $pc->msg;
    }

}

?>

<form class="bapers-form" method="post">

    <?php
    if ($data['router']->args == 'multi') {

        echo '<input style="display:none;" type="number" class="form-control" value="'. $_POST['amount'] .'" name="amount">';
        echo '<input style="display:none;" type="number" class="form-control" value="'. $_POST['cid'] .'" name="cid">';

        foreach ($_POST['jobs'] as $jid) {
            ?>
            
            <input style="display:none;" type="number" class="form-control" value="<?php echo $jid; ?>" name="jobs[]">

            <div style="margin-bottom:5px;" class="row">
                <div class="col">
                    <label>Job ID</label>
                    <input type="number" class="form-control" value="<?php echo $jid; ?>" disabled>
                </div>
                <div class="col">
                    <label>Job Price</label>
                    <input type="text" class="form-control" value="<?php echo '£' . $_POST['job-'.$jid]; ?>" disabled>
                </div>
            </div>
            <?php
        }
    } else {
    ?>
    
    <div style="margin-bottom:15px;" class="row">
        <div class="col">
            <label>Job ID</label>
            <input type="number" class="form-control" value="<?php echo $job->jid; ?>" disabled>
        </div>
        <div class="col">
            <label>Job Price</label>
            <input type="text" class="form-control" value="<?php echo '£' . $job->price; ?>" disabled>
        </div>
    </div>
    <?php
    }
    ?>

    <label style="margin-top:15px;">Payment Type</label>
    <select class="form-control" id="paymentType" name="type">
        <option value="0">Cash</option>
        <option value="1">Card</option>
    </select>

    <div style="margin-bottom:15px;display:none;" id="cardDetails">
        <label>Card Details</label>
        <div class="input-group">
            <span class="input-group-text"><i class="feather icon-credit-card"></i></span>
            <input class="form-control" type="text" placeholder="Card Type" name="cardType">
            <input class="form-control" type="number" placeholder="Card Last 4 Digits" name="cardNumber">
            <input class="form-control" type="number" placeholder="Card Expiry ( eg: 1223 )" name="cardExpiry">
        </div>
    </div>
    
    <button class="btn btn-primary" type="submit" name="recordPaymentSubmit">Record Payment</button>
</form>

<script type="text/javascript">
$(function() {
    var type = $('#paymentType'),
        cardDet = $('#cardDetails');
    type.change(function() {
        if ($(this).val()) {
            cardDet.show();
        } else {
            cardDet.hide();
        }
    });
});
</script>