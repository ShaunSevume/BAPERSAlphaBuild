<?php

// imports
import('controllers/customer.cont');
import('entities/customer.ent');

$cc = new CustomerController($data['db']);
$c = $cc->getCustomer($data['router']->args);

if ($cc->success) {

// update called
if (isset($_POST['editCustomerSubmit'])) {

    // create customer object
    $c = new Customer($c->cid, $_POST['type'], $_POST['name'], $_POST['cname'], $_POST['address'], $_POST['phone'], null, $_POST['discountType'], $_POST['discount']);

    // call updateCustomer
    $cc->updateCustomer($c);

    if ($cc->success) {
        echo '<div class="alert alert-success">Customer updated successfully!</div>';
    } else {
        echo $cc->msg;
    }

}
?>

<form class="bapers-form" method="POST">

    <label>Customer Number</label>
    <input class="form-control" type="text" value="<?php echo $c->cid; ?>" disabled>

    <?php
    if ($c->type) {
    ?>
    <label>Customer Wallet</label>
    <input class="form-control" type="text" value="£<?php echo $c->wallet; ?>" disabled>
    <?php
    }
    ?>

    <div style="float:right;" class="form-check">
        <input class="form-check-input" type="checkbox" id="bsCheck" name="business" <?php echo $c->cname ? 'checked' : ''; ?>>
        <label class="form-check-label" for="bsCheck">
            is a business?
        </label>
    </div>

    <label>Customer Name</label>
    <div class="input-group" style="margin-bottom:15px;">
        <input class="form-control" type="text" placeholder="Enter Customer name" name="name" value="<?php echo $c->name; ?>">
        <input id="cname" style="display:<?php echo $c->cname ? 'block' : 'none'; ?>;" class="form-control" type="text" placeholder="Enter Contact Name" name="cname" value="<?php echo $c->cname; ?>">
    </div>

    <label>Address</label>
    <input class="form-control" type="text" placeholder="Enter Customer Address" name="address" value="<?php echo $c->address; ?>">

    <label>Contact Number</label>
    <input class="form-control" type="text" placeholder="Enter Customer Number" name="phone" value="<?php echo $c->phone; ?>">

    <?php
    if ($data['user']->hasPermission(array('om'))) {
    ?>
    <label>Customer Type</label>
    <select id="customerType" class="form-control" name="type">
        <option value="0" <?php echo $c->type ? '' : 'selected'; ?>>Standard Customer</option>
        <option value="1" <?php echo $c->type ? 'selected' : ''; ?>>Valued Customer</option>
    </select>
    
    <div style="display:<?php echo $c->type ? 'block' : 'none'; ?>;" id="customerDiscountSection" class="input-group">
        <label>Discount</label>
        <div style="margin-bottom:15px;" class="input-group">
            <select id="discountType" class="form-control" name="discountType">
                <option value="0" <?php echo $c->discountType == 0 ? 'selected' : ''; ?>>No Discount</option>
                <option value="1" <?php echo $c->discountType == 1 ? 'selected' : ''; ?>>Fixed Discount</option>
                <option value="2" <?php echo $c->discountType == 2 ? 'selected' : ''; ?>>Variable Discount</option>
                <option value="3" <?php echo $c->discountType == 3 ? 'selected' : ''; ?>>Flexible Discount</option>
            </select>
            <input style="display:<?php if ($c->discountType == 1) { echo 'block'; } else { echo 'none'; } ?>;" id="discountAmount" class="form-control" type="text" placeholder="Enter Discount Amount without the %" value="<?php echo $c->discount; ?>" name="discount">
            <button id="editBoundsButton" style="display:<?php echo $c->discountType == 3 ? 'block' : 'none'; ?>" class="btn btn-primary">Edit Bounds</button>
        </div>
    </div>
    <?php
    }
    ?>

    <button class="btn btn-primary" type="submit" name="editCustomerSubmit">Update Customer</button>
</form>

<div id="flexibleDiscountModal" class="modal" tabindex="-1">
    <div style="max-width:600px;" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Flexible Discount Bounds</h5>
                <button type="button" class="btn-close flexibleGenerate" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="flexibleHolder" class="modal-body">
                <?php
                if ($c->discountType == 3) {
                $bounds = $c->parseBounds();
                foreach ($bounds as $i => $b) {
                ?>
                <div style="margin-top:5px;" class="input-group">
                    <input class="form-control flexibleAmount" type="text" placeholder="Minimum Spend Amount" value="<?php echo $b['min']; ?>">
                    <span class="input-group-text"><i class="feather icon-chevron-right"></i></span>
                    <input class="form-control flexiblePercentage" type="text" placeholder="Discount Percentage" value="<?php echo $b['pct']; ?>">
                    <?php
                    if ($i < count($bounds) - 1) {
                        echo '<button class="btn btn-primary minus" onclick="fabClick(this)"><i class="feather icon-minus"></i></button>';
                    } else {
                        echo '<button class="btn btn-primary plus" onclick="fabClick(this)"><i class="feather icon-plus"></i></button>';
                    }
                    ?>
                </div>
                <?php
                }
                } else {
                ?>
                <div class="input-group">
                    <input class="form-control flexibleAmount" type="text" placeholder="Minimum Spend Amount">
                    <span class="input-group-text"><i class="feather icon-chevron-right"></i></span>
                    <input class="form-control flexiblePercentage" type="text" placeholder="Discount Percentage">
                    <button class="btn btn-primary plus" onclick="fabClick(this)"><i class="feather icon-plus"></i></button>
                </div>
                <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary flexibleGenerate">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

var bs = $('#bsCheck'),
    cc = $('#cname'),
    ct = $('#customerType'),
    cds = $('#customerDiscountSection'),
    dt = $('#discountType'),
    da = $('#discountAmount'),
    ebb = $('#editBoundsButton'),
    modal = new bootstrap.Modal($('#flexibleDiscountModal')[0]),
    holder = $('#flexibleHolder'),
    generateBtn = $('.flexibleGenerate');


$(function() {

    // show contact name if customer if business account
    bs.change(function() {
        if (this.checked) {
            cc.show();
        } else {
            cc.hide();
        }
    });

    // if manager changes customer type
    ct.change(function() {
        if ($(this).val() == 1) {
            cds.show();
        } else {
            cds.hide();
            da.hide();
            ebb.hide();
            dt.val(0);
            da.attr('value', '');
            da.val('');
        }
    });

    // show necessary elements
    dt.change(function() {
        
        da.attr('value', '');
        da.val('');

        if ($(this).val() == 1) {
            da.show();
        } else {
            da.hide();
        }
        
        if ($(this).val() == 3) {
            ebb.show();
            modal.show();
        } else {
            ebb.hide();
        }
    });
    ebb.click(function(e) {
        modal.show();
        e.preventDefault();
    });

});

// flexible discount calculation
function fabClick(btn) {
    if ($(btn).hasClass('plus')) {
        $(btn).removeClass('plus');
        $(btn).addClass('minus');
        $(btn).html('<i class="feather icon-minus"></i>');
        holder.append('<div style="margin-top:5px;" class="input-group"><input class="form-control flexibleAmount" type="text" placeholder="Minimum Spend Amount"><span class="input-group-text"><i class="feather icon-chevron-right"></i></span><input class="form-control flexiblePercentage" type="text" placeholder="Discount Percentage"><button class="btn btn-primary plus" onclick="fabClick(this)"><i class="feather icon-plus"></i></button></div>');
    } else if ($(btn).hasClass('minus')) {
        $(btn).parent().remove();
    }
}

generateBtn.click(function() {
    var generatedText = '',
        bounds = holder.children('.input-group');
    bounds.each(function( i, bound ) {
        var amount = $(this).find('.flexibleAmount').val(), percentage = $(this).find('.flexiblePercentage').val();
        generatedText += amount + ' > ' + percentage;
        if (i < bounds.length - 1) {
            generatedText += '; ';
        }
    });
    da.attr('value', generatedText);
    da.val(generatedText);
    modal.hide();
});
</script>

<?php
}
?>