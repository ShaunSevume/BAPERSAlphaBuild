<?php

// imports
import('controllers/job.cont');
import('entities/job.ent');
import('controllers/payment.cont');
import('entities/payment.ent');
import('entities/card.ent');


$jc = new JobController($data['db']);
$jobs = $jc->getPaymentJobList();


if ($jc->success) {
    $mj = array('rl' => '', 'amount' => 0, 'count' => 0, 'jobIds' => array());
    
    foreach ($jobs as $k => $j) {
        
        if ($j['priority']) {

            if ($k + 2 <= count($jobs) && $jobs[$k + 1]['cid'] == $j['cid']) {
                
                $mj['rl'] .= 'Job: ' . $j['jid'] . '<i style="margin-left:5px;margin-right:5px;" class="feather icon-arrow-right"></i>' . '£' . $j['price'] . ' | Completed at <i style="margin-left:5px;margin-right:5px;" class="feather icon-arrow-right"></i> ' . date_format(date_create($j['completed']), 'd/m/Y - H:i') . '<br>';
                array_push($mj['jobIds'], array($j['jid'], $j['price']));
                $mj['amount'] += $j['price'];

            } else {
                ?>
                <div class="card bp-lv-card">
                    <div class="card-header text-white bg-dark">
                        <b>Multi Job</b> <i style="margin-left:5px;margin-right:5px;" class="feather icon-arrow-right"></i> £<?php echo $mj['amount'] += $j['price']; ?>
                        <form style="padding:0;" action="<?php echo WEB_CONFIG['home'] .'index.php?v=payments&s=recordpayment&jid=multi'; ?>" method="post" class="lv-close-cue">
                            <?php
                            
                            array_push($mj['jobIds'], array($j['jid'], $j['price']));
                            echo '<input style="display:none;" type="number" name="amount" value="'.$mj['amount'].'">';
                            echo '<input style="display:none;" type="number" name="cid" value="'.$j['cid'].'">';

                            foreach ($mj['jobIds'] as $k => $job) {
                                echo '<input style="display:none;" type="number" name="jobs[]" value="'.$job[0].'">';
                                echo '<input style="display:none;" type="number" name="job-'.$job[0].'" value="'.$job[1].'">';
                            }
                            ?>
                            <button class="btn btn-primary btn-sm" type="submit">Record Payment</button>
                        </form>
                    </div>
                    <div class="card-body lv-body-with-desc">
                        <?php echo $mj['rl'] .'Job: ' . $j['jid'] . '<i style="margin-left:5px;margin-right:5px;" class="feather icon-arrow-right"></i>' . '£' . $j['price']; ?>
                        <?php echo ' | Completed at <i style="margin-left:5px;margin-right:5px;" class="feather icon-arrow-right"></i>' . date_format(date_create($j['completed']), 'd/m/Y - H:i'); ?>
                    </div>
                    <div class="card-footer">
                        Customer: <?php echo $j['cid'] . ' | ' . $j['cname']; ?>
                    </div>
                </div>

                <?php
                $mj = array('rl' => '', 'amount' => 0, 'count' => 0, 'jobIds' => array());
            }

        } else {
            ?>

            <div class="card bp-lv-card">
                <div class="card-header text-white bg-dark">
                    <?php
                        echo '<b>Single Job:</b> ' . $j['jid'];
                        echo '<i style="margin-left:5px;margin-right:5px;" class="feather icon-arrow-right"></i>';
                        echo '£' . $j['price'] . ' | Completed at <i style="margin-left:5px;margin-right:5px;" class="feather icon-arrow-right"></i> ' . date_format(date_create($j['completed']), 'd/m/Y - H:i');
                        echo '<a href="'. WEB_CONFIG['home'] .'index.php?v=payments&s=recordpayment&jid='. $j['jid'] .'" class="btn btn-primary btn-sm lv-close-cue">Record Payment</a>';
                    ?>
                </div>
                <div class="card-footer">
                    Customer: <?php echo $j['cid'] . ' | ' . $j['cname']; ?>
                </div>
            </div>

            <?php
        }

    }
} else {
    echo $jc->msg;
}
?>