<?php
/**
 * Vehdoc Doorstep Delivery System
 *
 * @package Vehdoc
 */

if (!defined('ABSPATH')) exit;

/**
 * Delivery Meta Boxes
 */
function vehdoc_delivery_meta_boxes() {
    add_meta_box('vehdoc_delivery_details', __('Delivery Details', 'vehdoc'), 'vehdoc_delivery_details_cb', 'vehdoc_delivery', 'normal', 'high');
}
add_action('add_meta_boxes', 'vehdoc_delivery_meta_boxes');

function vehdoc_delivery_details_cb($post) {
    wp_nonce_field('vehdoc_delivery_meta', 'vehdoc_delivery_nonce');

    $order_id = get_post_meta($post->ID, '_vehdoc_delivery_order', true);
    $user_id  = get_post_meta($post->ID, '_vehdoc_delivery_user', true);
    $address  = get_post_meta($post->ID, '_vehdoc_delivery_address', true);
    $date     = get_post_meta($post->ID, '_vehdoc_delivery_date', true);
    $time     = get_post_meta($post->ID, '_vehdoc_delivery_time', true);
    $express  = get_post_meta($post->ID, '_vehdoc_delivery_express', true);
    $status   = get_post_meta($post->ID, '_vehdoc_delivery_status', true) ?: 'scheduled';
    $agent    = get_post_meta($post->ID, '_vehdoc_delivery_agent', true);
    $notes    = get_post_meta($post->ID, '_vehdoc_delivery_notes', true);

    $user = $user_id ? get_user_by('ID', $user_id) : null;

    $statuses = array(
        'scheduled'    => __('Scheduled', 'vehdoc'),
        'assigned'     => __('Agent Assigned', 'vehdoc'),
        'picked_up'    => __('Picked Up', 'vehdoc'),
        'in_transit'   => __('In Transit', 'vehdoc'),
        'delivered'    => __('Delivered', 'vehdoc'),
        'failed'       => __('Failed', 'vehdoc'),
    );

    // Get agents (users with vehdoc_agent role)
    $agents = get_users(array('role__in' => array('vehdoc_agent', 'administrator')));
    ?>
    <table class="form-table vehdoc-meta-table">
        <tr>
            <th><?php _e('Order', 'vehdoc'); ?></th>
            <td>
                <?php if ($order_id) : ?>
                    <a href="<?php echo get_edit_post_link($order_id); ?>">Order #<?php echo esc_html($order_id); ?></a>
                <?php else : ?>
                    —
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th><?php _e('Customer', 'vehdoc'); ?></th>
            <td><?php echo $user ? esc_html($user->display_name . ' — ' . $user->user_email) : '—'; ?></td>
        </tr>
        <tr>
            <th><?php _e('Address', 'vehdoc'); ?></th>
            <td><textarea name="vehdoc_delivery_address" class="large-text" rows="3"><?php echo esc_textarea($address); ?></textarea></td>
        </tr>
        <tr>
            <th><?php _e('Preferred Date', 'vehdoc'); ?></th>
            <td><input type="date" name="vehdoc_delivery_date" value="<?php echo esc_attr($date); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><?php _e('Preferred Time', 'vehdoc'); ?></th>
            <td><input type="time" name="vehdoc_delivery_time" value="<?php echo esc_attr($time); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><?php _e('Express Delivery', 'vehdoc'); ?></th>
            <td><?php echo $express ? '<strong style="color:green;">Yes</strong>' : 'No'; ?></td>
        </tr>
        <tr>
            <th><label for="vehdoc_delivery_status"><?php _e('Status', 'vehdoc'); ?></label></th>
            <td>
                <select id="vehdoc_delivery_status" name="vehdoc_delivery_status">
                    <?php foreach ($statuses as $key => $label) : ?>
                        <option value="<?php echo esc_attr($key); ?>" <?php selected($status, $key); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="vehdoc_delivery_agent"><?php _e('Assign Agent/Rider', 'vehdoc'); ?></label></th>
            <td>
                <select id="vehdoc_delivery_agent" name="vehdoc_delivery_agent">
                    <option value=""><?php _e('— Select Agent —', 'vehdoc'); ?></option>
                    <?php foreach ($agents as $a) : ?>
                        <option value="<?php echo esc_attr($a->ID); ?>" <?php selected($agent, $a->ID); ?>>
                            <?php echo esc_html($a->display_name . ' (' . $a->user_email . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="vehdoc_delivery_notes"><?php _e('Delivery Notes', 'vehdoc'); ?></label></th>
            <td><textarea id="vehdoc_delivery_notes" name="vehdoc_delivery_notes" rows="3" class="large-text"><?php echo esc_textarea($notes); ?></textarea></td>
        </tr>
    </table>
    <?php
}

function vehdoc_save_delivery_meta($post_id) {
    if (!isset($_POST['vehdoc_delivery_nonce']) || !wp_verify_nonce($_POST['vehdoc_delivery_nonce'], 'vehdoc_delivery_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array(
        'vehdoc_delivery_address' => '_vehdoc_delivery_address',
        'vehdoc_delivery_date'    => '_vehdoc_delivery_date',
        'vehdoc_delivery_time'    => '_vehdoc_delivery_time',
        'vehdoc_delivery_status'  => '_vehdoc_delivery_status',
        'vehdoc_delivery_agent'   => '_vehdoc_delivery_agent',
        'vehdoc_delivery_notes'   => '_vehdoc_delivery_notes',
    );

    foreach ($fields as $input => $meta_key) {
        if (isset($_POST[$input])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$input]));
        }
    }

    // If agent assigned, update status
    if (!empty($_POST['vehdoc_delivery_agent'])) {
        $current_status = get_post_meta($post_id, '_vehdoc_delivery_status', true);
        if ($current_status === 'scheduled') {
            update_post_meta($post_id, '_vehdoc_delivery_status', 'assigned');
        }
    }

    // If delivered, update order status
    if (isset($_POST['vehdoc_delivery_status']) && $_POST['vehdoc_delivery_status'] === 'delivered') {
        $order_id = get_post_meta($post_id, '_vehdoc_delivery_order', true);
        if ($order_id) {
            $old_status = get_post_meta($order_id, '_vehdoc_order_status', true);
            update_post_meta($order_id, '_vehdoc_order_status', 'delivered');
            vehdoc_log_status_change($order_id, $old_status, 'delivered');
            vehdoc_notify_status_change($order_id, 'delivered');
        }
    }
}
add_action('save_post_vehdoc_delivery', 'vehdoc_save_delivery_meta');

/**
 * Register Agent Role
 */
function vehdoc_register_agent_role() {
    if (!get_role('vehdoc_agent')) {
        add_role('vehdoc_agent', __('Delivery Agent', 'vehdoc'), array(
            'read'         => true,
            'upload_files' => true,
        ));
    }
}
add_action('init', 'vehdoc_register_agent_role');

/**
 * Admin Columns for Deliveries
 */
function vehdoc_delivery_columns($columns) {
    return array(
        'cb'      => '<input type="checkbox" />',
        'title'   => __('Delivery', 'vehdoc'),
        'order'   => __('Order', 'vehdoc'),
        'address' => __('Address', 'vehdoc'),
        'agent'   => __('Agent', 'vehdoc'),
        'status'  => __('Status', 'vehdoc'),
        'date'    => __('Date', 'vehdoc'),
    );
}
add_filter('manage_vehdoc_delivery_posts_columns', 'vehdoc_delivery_columns');

function vehdoc_delivery_column_data($column, $post_id) {
    switch ($column) {
        case 'order':
            $order_id = get_post_meta($post_id, '_vehdoc_delivery_order', true);
            echo $order_id ? '<a href="' . get_edit_post_link($order_id) . '">#' . esc_html($order_id) . '</a>' : '—';
            break;
        case 'address':
            echo esc_html(wp_trim_words(get_post_meta($post_id, '_vehdoc_delivery_address', true), 10));
            break;
        case 'agent':
            $agent_id = get_post_meta($post_id, '_vehdoc_delivery_agent', true);
            $agent = $agent_id ? get_user_by('ID', $agent_id) : null;
            echo $agent ? esc_html($agent->display_name) : '<em>Unassigned</em>';
            break;
        case 'status':
            $status = get_post_meta($post_id, '_vehdoc_delivery_status', true) ?: 'scheduled';
            $badges = array(
                'scheduled'  => '<span class="vehdoc-badge badge-pending">Scheduled</span>',
                'assigned'   => '<span class="vehdoc-badge badge-processing">Assigned</span>',
                'picked_up'  => '<span class="vehdoc-badge badge-processing">Picked Up</span>',
                'in_transit'  => '<span class="vehdoc-badge badge-approved">In Transit</span>',
                'delivered'  => '<span class="vehdoc-badge badge-delivered">Delivered</span>',
                'failed'     => '<span class="vehdoc-badge badge-cancelled">Failed</span>',
            );
            echo $badges[$status] ?? esc_html($status);
            break;
    }
}
add_action('manage_vehdoc_delivery_posts_custom_column', 'vehdoc_delivery_column_data', 10, 2);

/**
 * Nigerian States List
 */
function vehdoc_nigerian_states() {
    return array(
        'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue',
        'Borno', 'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu',
        'FCT - Abuja', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina',
        'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo',
        'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara',
    );
}
