<?php

// imports
import('controllers/customer.cont');
import('entities/customer.ent');

// ents
$cc = new CustomerController($data['db']);

if (isset($_POST['createCustomerSubmit'])) {

    $c = new Customer(null, null, $_POST['name'], $_POST['cname'], $_POST['address'], $_POST['phone'], null, null, null);
    $cc->createCustomer($c);

    if ($cc->success) {
        echo '<div class="alert alert-success">Customer created successfully!</div>';
    } else {
        echo $cc->msg;
    }

}

?>


<form class="bapers-form" method="POST">

    <div style="float:right;" class="form-check">
        <input class="form-check-input" type="checkbox" id="bsCheck" name="business">
        <label class="form-check-label" for="bsCheck">
            is a business?
        </label>
    </div>

    <label>Customer Name</label>
    <div class="input-group" style="margin-bottom:15px;">
        <input class="form-control" type="text" placeholder="Enter Customer name" name="name">
        <input id="cname" style="display:none;" class="form-control" type="text" placeholder="Enter Contact Name" name="cname">
    </div>

    <label>Address</label>
    <input class="form-control" type="text" placeholder="Enter Customer Address" name="address">

    <label>Contact Number</label>
    <input class="form-control" type="text" placeholder="Enter Customer Number" name="phone">

    <button class="btn btn-primary" type="submit" name="createCustomerSubmit">Create Customer</button>
</form>

<script>
$(function() {

    var bs = $('#bsCheck'),
        cc = $('#cname');

    bs.change(function() {
        if (this.checked) {
            cc.show();
        } else {
            cc.hide();
        }
    });

});
</script>