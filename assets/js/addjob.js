$(function() {

    // selected customer
    var selectedCustomer,

        surchargeAmount = $('#surchargeAmount'),
        variableTaskDiscount = $('.variableTaskDiscount'),
        taskBox = $('input[type="checkbox"]'),

        customerWallet = $("input[name='customerWallet']"),
        subTotalBox = $('#jSubTotal'),
        totalBox = $('#jTotal'),
        totalJobPrice = $('input[name="totalJobPrice"]');


    // calculate job prices
    function calculateJob() {

        //  values
        var subTotal = 0,
            total = 0,
            wallet = selectedCustomer.wallet;
        
        // calculate from the tasks
        taskBox.each(function(k, t) {

            // if task selected
            if (t.checked) {

                // calculating the subtotal
                subTotal += parseFloat($(t).attr('price'));
    
                // the total if variable discount
                if (selectedCustomer.discountType == 2) {
                    total += parseFloat($(t).attr('finalprice'));
                }
            }

        });

        
        // calculated discount
        total = subTotal;

        if (selectedCustomer.discountType == 1) {
            total = subTotal - (subTotal / 100 * parseFloat(selectedCustomer.discount));
        }


        // surcharge
        total = total + (total / 100 * parseFloat(surchargeAmount.attr('value')));

        // calculating the wallet
        if (selectedCustomer.wallet > 0) {
            
            // get the price
            var minusWallet = selectedCustomer.wallet - total;
            if (minusWallet < 0) {
                wallet = 0;
                total = Math.abs(minusWallet);
            } else {
                total = 0;
                wallet = minusWallet;
            }
        }


        // update the prices
        subTotalBox.text("£" + subTotal);
        totalBox.text("£" + total);

        totalJobPrice.attr('value', total);
        customerWallet.attr('value', wallet);
    }


    // resets values
    function reset() {



    }


    var box = $("input#customerSearchBox"),
        trigger = $('input#customerModalTrigger'),
        modal = new bootstrap.Modal($('#customerSearchModal')[0]),
        list = $('#customers-list'),
        surcharge = $('.surcharge'),
        vdInput = variableTaskDiscount.find('input[type=number]'),

        vd = $('#variableDiscountModal'),
        vdModal = new bootstrap.Modal(vd[0]),
        variableYesBtn = $('#variableYesBtn'),

        custid = $("input[name='customerId']"),
        urgency = $('#urgencySelect'),
        expTime = $('.expressTime'),

        omVerify = $('#omVerify');
    
    
    // customer search trigger
    trigger.focus(function() {
        modal.show();
    });


    // customer search
    box.keyup(function() {
        $.get('api.php?type=searchCustomer&s=' + box.val(), function( customers ) {
            
            // shows the customer in a list of buttons
            var listRendered = '';
            $(customers).each(function( i, c ) {
                listRendered += '<button class="form-control btn btn-primary customerListItem" style="margin-bottom:5px;" index="' + i + '">' + c.cid + ' | ' + c.name + '</button>';
            });
            list.html(listRendered);


            // when someone clicks on the customer
            $('.customerListItem').click(function() {
                
                // sets the customer details to selected customer
                selectedCustomer = customers[$(this).attr('index')];
                custid.attr('value', selectedCustomer.cid);

                // variable discount
                if (selectedCustomer.discountType == 2) {
                    
                    // resetting the values
                    vdModal.show();
                    vdInput.attr('discount', 0);
                    taskBox.attr('finalprice', '');
                    vdInput.prop('disabled', true);

                } else {
                    vdModal.hide();
                }

                // changing the customer name
                trigger.val($(this).text());
                modal.hide();

            });
        });
    });


    // URGENCY SHOWS SURCHARGE AND EXPRESS TIME
    urgency.change(function() {

        if ($(this).val() == 'normal') {
            surcharge.hide();
        } else {
            surcharge.show();
        }

        if ($(this).val() == 'express') {
            expTime.show();
        } else {
            expTime.hide();
        }

        // surcharge amount resets
        surchargeAmount.attr('value', 0);
        surchargeAmount.val(0);
        calculateJob();
    });

    // when surchange is changed
    surchargeAmount.change(function() {
        surchargeAmount.attr('value', surchargeAmount.val());
        calculateJob();
    });


    // variable discount apply
    variableYesBtn.click(function() {
        vd.find('#dcApplyMessage').hide();

        if (curUserPermission) {
            variableTaskDiscount.show();
            vdModal.hide();
        } else {
            vd.find('.modal-footer').hide();
            vd.find('.modal-title').text('Verification required!');
            omVerify.show();
        }
    });


    // Office Manager verify
    omVerify.submit(function(e) {
        $.post('api.php?type=verifyOM', $(this).serialize(), function(d) {
            if (d.success) {
                variableTaskDiscount.show();
                vdModal.hide();
            } else {
                var amsg = vd.find('#dcApplyMessage');
                amsg.text(d.msg);
                amsg.addClass('alert-danger');
                amsg.show();
            }
        });
        e.preventDefault();
    });


    // CHECKMARK FOR TASK SELECT
    taskBox.change(function() {

        if (selectedCustomer.discountType == 2) {
            if (this.checked) {
                $(this).parent().parent().parent().find('input[type=number]').prop('disabled', false);
                $(this).attr('finalprice', parseFloat($(this).attr('price')));
            } else {
                $(this).parent().parent().parent().find('input[type=number]').prop('disabled', true);
                $(this).attr('finalprice', '');
            }
        }


        // calculating job
        calculateJob();
        
    });


    // Variable discount input change
    vdInput.change(function() {

        var dis = $(this).val(),
            par = $(this).parent().parent(),
            check = par.find('input[type=checkbox]'),
            price = parseFloat(check.attr('price')),
            discountedPrice = price - (price / 100 * dis),

            priceBox = par.find('.taskPrice'),
            tpriceBox = par.find('.totalTaskPrice');

        check.attr('finalprice', discountedPrice);
        $(this).attr('discount', dis);

        if (dis == 0) {
            priceBox.removeClass('del');
            tpriceBox.text('');
        } else {
            priceBox.addClass('del');
            tpriceBox.text('£' + discountedPrice);
        }

        
        // calculating job
        calculateJob();

    });

});