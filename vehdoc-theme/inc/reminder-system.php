<?php
/**
 * Vehdoc Automated Reminder System
 *
 * @package Vehdoc
 */

if (!defined('ABSPATH')) exit;

/**
 * Schedule Reminder Cron Job
 */
function vehdoc_schedule_reminders() {
    if (!wp_next_scheduled('vehdoc_daily_reminder_check')) {
        wp_schedule_event(time(), 'daily', 'vehdoc_daily_reminder_check');
    }
}
add_action('wp', 'vehdoc_schedule_reminders');

/**
 * Clear Scheduled Event on Deactivation
 */
function vehdoc_clear_reminders() {
    wp_clear_scheduled_hook('vehdoc_daily_reminder_check');
}

/**
 * Daily Reminder Check
 */
function vehdoc_check_expiring_documents() {
    $reminder_days = get_option('vehdoc_reminder_days', array('30', '14', '7'));
    if (!is_array($reminder_days)) {
        $reminder_days = array('30', '14', '7');
    }

    foreach ($reminder_days as $days) {
        $target_date = date('Y-m-d', strtotime("+{$days} days"));
        vehdoc_send_expiry_reminders($target_date, intval($days));
    }
}
add_action('vehdoc_daily_reminder_check', 'vehdoc_check_expiring_documents');

/**
 * Send Expiry Reminders
 */
function vehdoc_send_expiry_reminders($target_date, $days_before) {
    $orders = get_posts(array(
        'post_type'      => 'vehdoc_order',
        'posts_per_page' => -1,
        'meta_query'     => array(
            'relation' => 'AND',
            array('key' => '_vehdoc_order_status', 'value' => 'delivered'),
            array('key' => '_vehdoc_expiry_date', 'value' => $target_date),
        ),
    ));

    foreach ($orders as $order) {
        $user_id = get_post_meta($order->ID, '_vehdoc_order_user', true);
        $user    = get_user_by('ID', $user_id);
        if (!$user) continue;

        $reminder_key = '_vehdoc_reminder_' . $days_before . '_sent';
        if (get_post_meta($order->ID, $reminder_key, true)) continue;

        $service_id = get_post_meta($order->ID, '_vehdoc_order_service', true);
        $service    = $service_id ? get_post($service_id) : null;
        $expiry     = get_post_meta($order->ID, '_vehdoc_expiry_date', true);

        // Send Email
        vehdoc_send_reminder_email($user, $order, $service, $days_before, $expiry);

        // Send SMS if configured
        vehdoc_send_reminder_sms($user_id, $order, $service, $days_before, $expiry);

        // Dashboard notification
        $notifications = get_user_meta($user_id, 'vehdoc_notifications', true) ?: array();
        array_unshift($notifications, array(
            'type'      => 'expiry_reminder',
            'order_id'  => $order->ID,
            'message'   => sprintf(
                'Your %s expires in %d days (on %s). Renew now!',
                $service ? $service->post_title : 'document',
                $days_before,
                date('M j, Y', strtotime($expiry))
            ),
            'read'      => false,
            'timestamp' => current_time('mysql'),
        ));
        update_user_meta($user_id, 'vehdoc_notifications', array_slice($notifications, 0, 50));

        update_post_meta($order->ID, $reminder_key, true);
    }
}

/**
 * Send Reminder Email
 */
function vehdoc_send_reminder_email($user, $order, $service, $days, $expiry) {
    $subject = sprintf(
        '[Vehdoc] Reminder: Your %s expires in %d days',
        $service ? $service->post_title : 'document',
        $days
    );

    $renew_url = home_url('/services/');

    $message = sprintf(
        "Hi %s,\n\nThis is a reminder that your %s is expiring on %s (%d days from now).\n\nDon't wait until the last minute — renew now and avoid penalties or delays.\n\nRenew Now: %s\n\nOrder Reference: VHD-%s\n\nIf you've already renewed, please disregard this message.\n\n— Vehdoc Team\nFast, Secure Vehicle Documentation Services",
        $user->display_name,
        $service ? $service->post_title : 'vehicle document',
        date('F j, Y', strtotime($expiry)),
        $days,
        $renew_url,
        str_pad($order->ID, 6, '0', STR_PAD_LEFT)
    );

    wp_mail($user->user_email, $subject, $message);
}

/**
 * Send Reminder SMS
 */
function vehdoc_send_reminder_sms($user_id, $order, $service, $days, $expiry) {
    $api_key   = get_option('vehdoc_sms_api_key');
    $sender_id = get_option('vehdoc_sms_sender_id', 'VEHDOC');

    if (empty($api_key)) return;

    $phone = get_user_meta($user_id, 'vehdoc_phone', true);
    if (empty($phone)) return;

    $message = sprintf(
        'Vehdoc Reminder: Your %s expires in %d days (on %s). Renew now at %s to avoid penalties.',
        $service ? $service->post_title : 'vehicle document',
        $days,
        date('M j, Y', strtotime($expiry)),
        home_url('/services/')
    );

    // Generic SMS API call - adapt to specific provider
    wp_remote_post('https://api.ng.termii.com/api/sms/send', array(
        'body' => json_encode(array(
            'to'      => $phone,
            'from'    => $sender_id,
            'sms'     => $message,
            'type'    => 'plain',
            'channel' => 'generic',
            'api_key' => $api_key,
        )),
        'headers' => array('Content-Type' => 'application/json'),
        'timeout' => 30,
    ));
}

/**
 * Admin: Set Document Expiry Date
 */
function vehdoc_add_expiry_meta_box() {
    add_meta_box('vehdoc_expiry', __('Document Expiry', 'vehdoc'), 'vehdoc_expiry_meta_box_cb', 'vehdoc_order', 'side', 'default');
}
add_action('add_meta_boxes', 'vehdoc_add_expiry_meta_box');

function vehdoc_expiry_meta_box_cb($post) {
    $expiry = get_post_meta($post->ID, '_vehdoc_expiry_date', true);
    ?>
    <p>
        <label for="vehdoc_expiry_date"><?php _e('Expiry Date:', 'vehdoc'); ?></label>
        <input type="date" id="vehdoc_expiry_date" name="vehdoc_expiry_date" value="<?php echo esc_attr($expiry); ?>" class="widefat">
    </p>
    <p class="description"><?php _e('Set the document expiry date to enable automatic renewal reminders.', 'vehdoc'); ?></p>
    <?php
}

function vehdoc_save_expiry_date($post_id) {
    if (isset($_POST['vehdoc_expiry_date'])) {
        update_post_meta($post_id, '_vehdoc_expiry_date', sanitize_text_field($_POST['vehdoc_expiry_date']));
    }
}
add_action('save_post_vehdoc_order', 'vehdoc_save_expiry_date');
