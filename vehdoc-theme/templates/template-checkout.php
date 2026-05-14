<?php
/**
 * Template Name: Checkout
 *
 * @package Vehdoc
 */

if (!is_user_logged_in()) {
    wp_redirect(home_url('/login/'));
    exit;
}

$order_id = intval($_GET['order_id'] ?? 0);
$order    = $order_id ? get_post($order_id) : null;
$user     = wp_get_current_user();

if (!$order || get_post_meta($order_id, '_vehdoc_order_user', true) != $user->ID) {
    wp_redirect(home_url('/dashboard/?tab=orders'));
    exit;
}

$service_id = get_post_meta($order_id, '_vehdoc_order_service', true);
$service    = get_post($service_id);
$amount     = get_post_meta($order_id, '_vehdoc_order_amount', true);
$status     = get_post_meta($order_id, '_vehdoc_order_status', true);

get_header(); ?>

<section class="checkout-section">
    <div class="container">
        <div class="checkout-container">
            <div class="checkout-summary">
                <h2>Order Summary</h2>
                <div class="checkout-card">
                    <div class="checkout-item">
                        <span class="ci-label">Order Number</span>
                        <span class="ci-value">VHD-<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?></span>
                    </div>
                    <div class="checkout-item">
                        <span class="ci-label">Service</span>
                        <span class="ci-value"><?php echo $service ? esc_html($service->post_title) : '—'; ?></span>
                    </div>
                    <div class="checkout-item">
                        <span class="ci-label">Fast Track</span>
                        <span class="ci-value"><?php echo get_post_meta($order_id, '_vehdoc_fast_track', true) ? 'Yes' : 'No'; ?></span>
                    </div>
                    <div class="checkout-item total">
                        <span class="ci-label">Total Amount</span>
                        <span class="ci-value">₦<?php echo number_format(floatval($amount)); ?></span>
                    </div>
                </div>
            </div>

            <div class="checkout-payment">
                <h2>Choose Payment Method</h2>
                <div class="payment-methods">
                    <button class="payment-method-btn active" data-gateway="paystack" id="paystackBtn">
                        <i class="fa-solid fa-credit-card"></i>
                        <div>
                            <strong>Paystack</strong>
                            <span>Card, Bank Transfer, USSD</span>
                        </div>
                    </button>
                    <button class="payment-method-btn" data-gateway="flutterwave" id="flutterwaveBtn">
                        <i class="fa-solid fa-wallet"></i>
                        <div>
                            <strong>Flutterwave</strong>
                            <span>Card, Bank, Mobile Money</span>
                        </div>
                    </button>
                </div>

                <div class="payment-info">
                    <div class="payment-security">
                        <i class="fa-solid fa-lock"></i>
                        <span>Your payment is secured with 256-bit SSL encryption</span>
                    </div>
                </div>

                <button class="btn btn-primary btn-block btn-lg" id="payNowBtn"
                    data-order-id="<?php echo $order_id; ?>"
                    data-amount="<?php echo floatval($amount); ?>"
                    data-email="<?php echo esc_attr($user->user_email); ?>"
                    data-name="<?php echo esc_attr($user->display_name); ?>"
                    data-phone="<?php echo esc_attr(get_user_meta($user->ID, 'vehdoc_phone', true)); ?>">
                    <i class="fa-solid fa-shield-halved"></i> Pay ₦<?php echo number_format(floatval($amount)); ?> Now
                </button>

                <p class="checkout-note">
                    By completing this payment, you agree to our <a href="<?php echo home_url('/terms-of-service/'); ?>">Terms of Service</a>.
                </p>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
