/**
 * Vehdoc Payment JavaScript
 *
 * @package Vehdoc
 */

(function($) {
    'use strict';

    var selectedGateway = 'paystack';

    // Payment method selection
    document.querySelectorAll('.payment-method-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.payment-method-btn').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            selectedGateway = this.dataset.gateway;
        });
    });

    // Pay Now button
    var payNowBtn = document.getElementById('payNowBtn');
    if (payNowBtn) {
        payNowBtn.addEventListener('click', function() {
            var orderId = this.dataset.orderId;
            var amount = parseFloat(this.dataset.amount);
            var email = this.dataset.email;
            var name = this.dataset.name;
            var phone = this.dataset.phone;

            if (selectedGateway === 'paystack') {
                payWithPaystack(orderId, amount, email, name, phone);
            } else {
                payWithFlutterwave(orderId, amount, email, name, phone);
            }
        });
    }

    function payWithPaystack(orderId, amount, email, name, phone) {
        if (typeof PaystackPop === 'undefined') {
            alert('Paystack is loading. Please try again.');
            return;
        }

        var handler = PaystackPop.setup({
            key: vehdocPayment.paystackPublicKey,
            email: email,
            amount: amount * 100,
            currency: 'NGN',
            ref: 'VHD_' + orderId + '_' + Date.now(),
            metadata: {
                order_id: orderId,
                custom_fields: [
                    { display_name: 'Customer Name', variable_name: 'customer_name', value: name },
                    { display_name: 'Phone Number', variable_name: 'phone', value: phone }
                ]
            },
            callback: function(response) {
                verifyPayment(response.reference, 'paystack', orderId);
            },
            onClose: function() {
                // Payment cancelled
            }
        });
        handler.openIframe();
    }

    function payWithFlutterwave(orderId, amount, email, name, phone) {
        if (typeof FlutterwaveCheckout === 'undefined') {
            alert('Flutterwave is loading. Please try again.');
            return;
        }

        FlutterwaveCheckout({
            public_key: vehdocPayment.flutterwavePublicKey,
            tx_ref: 'VHD_' + orderId + '_' + Date.now(),
            amount: amount,
            currency: 'NGN',
            payment_options: 'card, banktransfer, ussd',
            customer: {
                email: email,
                name: name,
                phone_number: phone
            },
            meta: { order_id: orderId },
            customizations: {
                title: 'Vehdoc Payment',
                description: 'Payment for vehicle documentation services',
                logo: vehdocPayment.siteUrl + '/wp-content/themes/vehdoc-theme/screenshot.png'
            },
            callback: function(response) {
                if (response.status === 'successful') {
                    verifyPayment(response.transaction_id, 'flutterwave', orderId);
                }
            },
            onclose: function() {
                // Payment cancelled
            }
        });
    }

    function verifyPayment(reference, gateway, orderId) {
        payNowBtn.disabled = true;
        payNowBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verifying payment...';

        $.ajax({
            url: vehdocPayment.restUrl + 'payments/verify',
            type: 'POST',
            data: JSON.stringify({
                reference: reference,
                gateway: gateway,
                order_id: orderId
            }),
            contentType: 'application/json',
            headers: { 'X-WP-Nonce': vehdocPayment.nonce },
            success: function(res) {
                if (res.success) {
                    payNowBtn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Payment Successful!';
                    payNowBtn.style.background = '#10b981';
                    payNowBtn.style.borderColor = '#10b981';
                    setTimeout(function() {
                        window.location.href = vehdocPayment.dashboardUrl + '?tab=tracking&order_id=' + orderId;
                    }, 2000);
                } else {
                    payNowBtn.disabled = false;
                    payNowBtn.innerHTML = '<i class="fa-solid fa-shield-halved"></i> Pay ₦' + parseFloat(payNowBtn.dataset.amount).toLocaleString() + ' Now';
                    alert('Payment verification failed. Please contact support.');
                }
            },
            error: function() {
                payNowBtn.disabled = false;
                payNowBtn.innerHTML = '<i class="fa-solid fa-shield-halved"></i> Pay ₦' + parseFloat(payNowBtn.dataset.amount).toLocaleString() + ' Now';
                alert('An error occurred verifying your payment. Please contact support.');
            }
        });
    }

})(jQuery);
