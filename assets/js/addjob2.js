$(function() {

    // if time remains, resetValues() calculateJob() 
    


    var box = $("input#customerSearchBox"),
        trigger = $('input#customerModalTrigger'),
        vd = $('#variableDiscountModal'),
        modal = new bootstrap.Modal($('#customerSearchModal')[0]),
        vdModal = new bootstrap.Modal(vd[0]),
        list = $('#customers-list'),
        custid = $("input[name='customerId']"),
        customerWallet = $("input[name='customerWallet']"),
        discountType = $("input[name='discountType']"),
        urgency = $('#urgencySelect'),
        expTime = $('.expressTime'),
        subTotalBox = $('#jSubTotal'),
        totalBox = $('#jTotal'),
        taskBox = $('input[type="checkbox"]'),
        totalJobPrice = $('input[name="totalJobPrice"]'),
        variableTaskDiscount = $('.variableTaskDiscount'),
        variableYesBtn = $('#variableYesBtn'),
        omVerify = $('#omVerify'),
        vdInput = variableTaskDiscount.find('input[type=number]'),
        selectedCustomer,
        total = 0,
        subTotal = 0,
        cWallet;
    
    // customer search trigger
    trigger.focus(function() {
        modal.show();
    });

    // customer search
    box.keyup(function() {
        $.get('api.php?type=searchCustomer&s=' + box.val(), function( customers ) {
            var listRendered = '';
            $(customers).each(function( i, c ) {
                listRendered += '<button class="form-control btn btn-primary customerListItem" style="margin-bottom:5px;" index="' + i + '">' + c.cid + ' | ' + c.name + '</button>';
            });
            list.html(listRendered);

            $('.customerListItem').click(function() {
                selectedCustomer = customers[$(this).attr('index')];
                custid.attr('value', selectedCustomer.cid);
                discountType.attr('value', selectedCustomer.discountType);

                // variable
                if (selectedCustomer.discountType == 2) {
                    vdModal.show();
                    vdInput.attr('discount', 0);
                    vdInput.prop('disabled', true);
                } else {
                    vdModal.hide();
                }

                trigger.val($(this).text());
                modal.hide();

            });
        });
    });

    // urgent -> need to implement surcharge
    urgency.change(function() {
        if ($(this).val() == 'express') {
            expTime.show();
        } else {
            expTime.hide();
        }
    });

    // for select
    taskBox.change(function() {
        if (this.checked) {
            subTotal += parseFloat($(this).attr('price'));
            
            if (selectedCustomer.discountType == 2) {
                $(this).parent().parent().parent().find('input[type=number]').prop('disabled', false);
                $(this).attr('finalprice', parseFloat($(this).attr('price')));

                if ($(this).attr('finalprice') == 0) {
                    total += parseFloat($(this).attr('price'));
                } else {
                    total += parseFloat($(this).attr('finalPrice'));
                }
            }
        } else {
            subTotal -= parseFloat($(this).attr('price'));
            if (selectedCustomer.discountType == 2) {
                $(this).parent().parent().parent().find('input[type=number]').prop('disabled', true);
                if ($(this).attr('finalprice') == 0) {
                    total -= parseFloat($(this).attr('price'));
                } else {
                    total -= parseFloat($(this).attr('finalprice'));
                    $(this).attr('finalprice', 0);
                }
            }
        }

        // discount logic
        if (selectedCustomer.discountType == 1) {
            total = subTotal - (subTotal / 100 * parseFloat(selectedCustomer.discount));
        } else if (selectedCustomer.discountType == 3) {
            $(selectedCustomer.discount).each(function( i, v ) {
                if (subTotal >= parseFloat(v.min)) {
                    total = subTotal - (subTotal / 100 * parseFloat(v.pct));
                } else if (subTotal == 0) {
                    total = 0;
                }
            });
        }

        

        totalJobPrice.attr('value', total);
        subTotalBox.text('£' + subTotal);
        totalBox.text('£' + total);
        
    });

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

    // change input
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

        // the total
        total = 0;
        taskBox.each(function(i, v) {
            if ($(v).attr('finalprice') !== undefined) {
                total += parseFloat($(v).attr('finalprice'));

                if (total - selectedCustomer.wallet > 0) {
                    tempTotal = total;
                    total = tempTotal - selectedCustomer.wallet;
                    selectedCustomer.wallet = 0;
                } else {
                    tempTotal = total;
                    total = 0;
                    selectedCustomer.wallet = selectedCustomer.wallet - tempTotal;
                }
                customerWallet.attr('value', selectedCustomer.wallet);
                
            }
        });

        totalJobPrice.attr('value', total);
        subTotalBox.text('£' + subTotal);
        totalBox.text('£' + total);

    });

});