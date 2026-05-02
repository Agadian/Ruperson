<?php
/**
 * Flutterwave Payment Gateway for WooCommerce
 */

if (!defined('ABSPATH')) exit;

function jackie_init_flutterwave_gateway() {
    if (!class_exists('WC_Payment_Gateway')) return;

    class WC_Gateway_Flutterwave extends WC_Payment_Gateway {

        public function __construct() {
            $this->id                 = 'flutterwave';
            $this->icon               = '';
            $this->has_fields         = false;
            $this->method_title       = __('Flutterwave', 'jackie-theme');
            $this->method_description = __('Accept payments via Flutterwave (card, bank transfer, mobile money, USSD).', 'jackie-theme');

            $this->init_form_fields();
            $this->init_settings();

            $this->title       = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->enabled     = $this->get_option('enabled');
            $this->testmode    = 'yes' === $this->get_option('testmode');
            $this->public_key  = $this->testmode ? $this->get_option('test_public_key') : $this->get_option('live_public_key');
            $this->secret_key  = $this->testmode ? $this->get_option('test_secret_key') : $this->get_option('live_secret_key');

            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_api_flutterwave_callback', array($this, 'handle_callback'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_flutterwave_script'));
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title'   => __('Enable/Disable', 'jackie-theme'),
                    'type'    => 'checkbox',
                    'label'   => __('Enable Flutterwave Payment', 'jackie-theme'),
                    'default' => 'no',
                ),
                'title' => array(
                    'title'       => __('Title', 'jackie-theme'),
                    'type'        => 'text',
                    'description' => __('Payment method title displayed at checkout.', 'jackie-theme'),
                    'default'     => __('Pay with Flutterwave', 'jackie-theme'),
                ),
                'description' => array(
                    'title'       => __('Description', 'jackie-theme'),
                    'type'        => 'textarea',
                    'description' => __('Payment method description displayed at checkout.', 'jackie-theme'),
                    'default'     => __('Pay securely using your card, bank transfer, or mobile money via Flutterwave.', 'jackie-theme'),
                ),
                'testmode' => array(
                    'title'       => __('Test Mode', 'jackie-theme'),
                    'type'        => 'checkbox',
                    'label'       => __('Enable Test Mode', 'jackie-theme'),
                    'default'     => 'yes',
                    'description' => __('Use test API keys for testing.', 'jackie-theme'),
                ),
                'test_public_key' => array(
                    'title' => __('Test Public Key', 'jackie-theme'),
                    'type'  => 'text',
                ),
                'test_secret_key' => array(
                    'title' => __('Test Secret Key', 'jackie-theme'),
                    'type'  => 'password',
                ),
                'live_public_key' => array(
                    'title' => __('Live Public Key', 'jackie-theme'),
                    'type'  => 'text',
                ),
                'live_secret_key' => array(
                    'title' => __('Live Secret Key', 'jackie-theme'),
                    'type'  => 'password',
                ),
            );
        }

        public function enqueue_flutterwave_script() {
            if (is_checkout() && $this->enabled === 'yes') {
                wp_enqueue_script('flutterwave-inline', 'https://checkout.flutterwave.com/v3.js', array(), null, true);
                wp_enqueue_script('jackie-flutterwave', JACKIE_URI . '/js/flutterwave-checkout.js', array('flutterwave-inline', 'jquery'), JACKIE_VERSION, true);
                wp_localize_script('jackie-flutterwave', 'flutterwaveParams', array(
                    'public_key'  => $this->public_key,
                    'callback_url' => WC()->api_request_url('flutterwave_callback'),
                ));
            }
        }

        public function process_payment($order_id) {
            $order = wc_get_order($order_id);

            return array(
                'result'   => 'success',
                'redirect' => $order->get_checkout_payment_url(true),
            );
        }

        public function handle_callback() {
            if (!isset($_GET['transaction_id'])) {
                wp_die('Invalid callback');
            }

            $transaction_id = sanitize_text_field($_GET['transaction_id']);

            $response = wp_remote_get('https://api.flutterwave.com/v3/transactions/' . $transaction_id . '/verify', array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $this->secret_key,
                    'Content-Type'  => 'application/json',
                ),
            ));

            if (is_wp_error($response)) {
                wp_die('Verification failed');
            }

            $body = json_decode(wp_remote_retrieve_body($response), true);

            if ($body['status'] === 'success' && $body['data']['status'] === 'successful') {
                $order_id = absint($body['data']['meta']['order_id'] ?? 0);
                $order = wc_get_order($order_id);

                if ($order) {
                    $order->payment_complete($transaction_id);
                    $order->add_order_note('Flutterwave payment successful. Transaction ID: ' . $transaction_id);
                    WC()->cart->empty_cart();
                    wp_redirect($this->get_return_url($order));
                    exit;
                }
            }

            wp_die('Payment verification failed. Please contact support.');
        }
    }
}
add_action('plugins_loaded', 'jackie_init_flutterwave_gateway');

function jackie_add_flutterwave_gateway($gateways) {
    if (class_exists('WC_Gateway_Flutterwave')) {
        $gateways[] = 'WC_Gateway_Flutterwave';
    }
    return $gateways;
}
add_filter('woocommerce_payment_gateways', 'jackie_add_flutterwave_gateway');
