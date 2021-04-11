<?php

// imports
import('controllers/customer.cont');
import('entities/customer.ent');

// settings
$cc = new CustomerController($data['db']);
$cl = $cc->getCustomerList();

if ($cc->success) {
foreach ($cl as $customer) {
?>

<a href="<?php echo WEB_CONFIG['home'].'index.php?v=customers&s=editcustomer&cid=' . $customer->cid; ?>" 
class="card bp-lv-card text-white bg-<?php echo $customer->type ? 'warning' : 'secondary'; ?>">
    <div class="card-header">
        <i class="feather icon-<?php echo $customer->type ? 'user-plus' : 'user'; ?>"></i>
        <?php echo $customer->cid . ' | ' . $customer->name; ?>
    </div>
</a>

<?php
}
echo '<a href="'. WEB_CONFIG['home'] . 'index.php?v=customers&s=createcustomer" class="btn btn-primary"><i style="margin-right:10px;" class="feather icon-plus"></i>Create Customer</a>';
}
?>