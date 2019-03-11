$(document).ready(function () {
    $('#add_payment_form').validate({// initialize the plugin

        rules: {
            payment_amount: {
                required: true
            },
            payment_date: {
                required: true,
                dateISO: true
            }
        }
    });

    $('#add_account_form').validate({// initialize the plugin

        rules: {
        }
    });
});
