/**
 * Flutterwave Checkout Integration
 */
(function($) {
    'use strict';

    $(document).on('click', '#place_order', function(e) {
        var selectedPayment = $('input[name="payment_method"]:checked').val();
        if (selectedPayment !== 'flutterwave') return;

        e.preventDefault();

        var form = $('form.checkout');
        var formData = form.serialize();

        // First submit order via AJAX
        $.ajax({
            type: 'POST',
            url: wc_checkout_params.checkout_url,
            data: formData,
            dataType: 'json',
            success: function(result) {
                if (result.result === 'success') {
                    // Extract order details from the page
                    var email = $('#billing_email').val();
                    var name = $('#billing_first_name').val() + ' ' + $('#billing_last_name').val();
                    var phone = $('#billing_phone').val();
                    var amount = parseFloat($('.order-total .amount').text().replace(/[^0-9.]/g, ''));
                    var orderId = result.redirect.match(/order-pay\/(\d+)/);
                    orderId = orderId ? orderId[1] : '0';

                    FlutterwaveCheckout({
                        public_key: flutterwaveParams.public_key,
                        tx_ref: 'JACKIE-' + orderId + '-' + Date.now(),
                        amount: amount,
                        currency: 'NGN',
                        payment_options: 'card,banktransfer,ussd,mobilemoney',
                        meta: {
                            order_id: orderId,
                        },
                        customer: {
                            email: email,
                            phone_number: phone,
                            name: name,
                        },
                        customizations: {
                            title: 'Jackie Creative Agency',
                            description: 'Payment for Order #' + orderId,
                            logo: '',
                        },
                        callback: function(data) {
                            if (data.status === 'successful') {
                                window.location.href = flutterwaveParams.callback_url +
                                    '?transaction_id=' + data.transaction_id;
                            }
                        },
                        onclose: function() {
                            // User closed the payment modal
                        },
                    });
                } else if (result.messages) {
                    // Show validation errors
                    $('.woocommerce-error, .woocommerce-message').remove();
                    form.prepend(result.messages);
                    $('html, body').animate({ scrollTop: form.offset().top - 100 }, 500);
                }
            }
        });
    });
})(jQuery);
