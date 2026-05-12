<?php
/**
 * Vehdoc Theme Options (Customizer + Settings Page)
 *
 * @package Vehdoc
 */

if (!defined('ABSPATH')) exit;

/**
 * Register Settings Page
 */
function vehdoc_register_settings() {
    // General Settings
    register_setting('vehdoc_settings', 'vehdoc_company_name');
    register_setting('vehdoc_settings', 'vehdoc_company_phone');
    register_setting('vehdoc_settings', 'vehdoc_company_email');
    register_setting('vehdoc_settings', 'vehdoc_company_address');
    register_setting('vehdoc_settings', 'vehdoc_whatsapp_number');

    // Payment Settings
    register_setting('vehdoc_settings', 'vehdoc_paystack_public_key');
    register_setting('vehdoc_settings', 'vehdoc_paystack_secret_key');
    register_setting('vehdoc_settings', 'vehdoc_flutterwave_public_key');
    register_setting('vehdoc_settings', 'vehdoc_flutterwave_secret_key');
    register_setting('vehdoc_settings', 'vehdoc_payment_mode'); // test or live

    // Notification Settings
    register_setting('vehdoc_settings', 'vehdoc_sms_api_key');
    register_setting('vehdoc_settings', 'vehdoc_sms_sender_id');
    register_setting('vehdoc_settings', 'vehdoc_reminder_days');

    // Delivery Settings
    register_setting('vehdoc_settings', 'vehdoc_delivery_fee');
    register_setting('vehdoc_settings', 'vehdoc_express_delivery_fee');
    register_setting('vehdoc_settings', 'vehdoc_delivery_states');

    // Social Links
    register_setting('vehdoc_settings', 'vehdoc_facebook');
    register_setting('vehdoc_settings', 'vehdoc_twitter');
    register_setting('vehdoc_settings', 'vehdoc_instagram');
    register_setting('vehdoc_settings', 'vehdoc_linkedin');

    // Promo & Referral
    register_setting('vehdoc_settings', 'vehdoc_enable_referral');
    register_setting('vehdoc_settings', 'vehdoc_referral_bonus');
    register_setting('vehdoc_settings', 'vehdoc_enable_promo');
}
add_action('admin_init', 'vehdoc_register_settings');

/**
 * Add Settings Page to Admin Menu
 */
function vehdoc_admin_menu() {
    add_menu_page(
        __('Vehdoc Settings', 'vehdoc'),
        __('Vehdoc', 'vehdoc'),
        'manage_options',
        'vehdoc-settings',
        'vehdoc_settings_page',
        'dashicons-car',
        30
    );

    add_submenu_page('vehdoc-settings', __('General', 'vehdoc'), __('General', 'vehdoc'), 'manage_options', 'vehdoc-settings', 'vehdoc_settings_page');
    add_submenu_page('vehdoc-settings', __('Payment', 'vehdoc'), __('Payment', 'vehdoc'), 'manage_options', 'vehdoc-payment', 'vehdoc_payment_page');
    add_submenu_page('vehdoc-settings', __('Analytics', 'vehdoc'), __('Analytics', 'vehdoc'), 'manage_options', 'vehdoc-analytics', 'vehdoc_analytics_page');
    add_submenu_page('vehdoc-settings', __('Notifications', 'vehdoc'), __('Notifications', 'vehdoc'), 'manage_options', 'vehdoc-notifications', 'vehdoc_notifications_settings_page');
}
add_action('admin_menu', 'vehdoc_admin_menu');

/**
 * General Settings Page
 */
function vehdoc_settings_page() {
    ?>
    <div class="wrap vehdoc-admin-wrap">
        <h1><i class="dashicons dashicons-car"></i> <?php _e('Vehdoc Settings', 'vehdoc'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('vehdoc_settings'); ?>

            <div class="vehdoc-admin-card">
                <h2><?php _e('Company Information', 'vehdoc'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th><label for="vehdoc_company_name"><?php _e('Company Name', 'vehdoc'); ?></label></th>
                        <td><input type="text" id="vehdoc_company_name" name="vehdoc_company_name" value="<?php echo esc_attr(get_option('vehdoc_company_name', 'Vehdoc')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="vehdoc_company_phone"><?php _e('Phone Number', 'vehdoc'); ?></label></th>
                        <td><input type="tel" id="vehdoc_company_phone" name="vehdoc_company_phone" value="<?php echo esc_attr(get_option('vehdoc_company_phone')); ?>" class="regular-text" placeholder="+234..."></td>
                    </tr>
                    <tr>
                        <th><label for="vehdoc_company_email"><?php _e('Email', 'vehdoc'); ?></label></th>
                        <td><input type="email" id="vehdoc_company_email" name="vehdoc_company_email" value="<?php echo esc_attr(get_option('vehdoc_company_email')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="vehdoc_company_address"><?php _e('Address', 'vehdoc'); ?></label></th>
                        <td><textarea id="vehdoc_company_address" name="vehdoc_company_address" class="large-text" rows="3"><?php echo esc_textarea(get_option('vehdoc_company_address')); ?></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="vehdoc_whatsapp_number"><?php _e('WhatsApp Number', 'vehdoc'); ?></label></th>
                        <td><input type="tel" id="vehdoc_whatsapp_number" name="vehdoc_whatsapp_number" value="<?php echo esc_attr(get_option('vehdoc_whatsapp_number')); ?>" class="regular-text" placeholder="+234..."></td>
                    </tr>
                </table>
            </div>

            <div class="vehdoc-admin-card">
                <h2><?php _e('Social Media Links', 'vehdoc'); ?></h2>
                <table class="form-table">
                    <?php
                    $socials = array(
                        'vehdoc_facebook'  => 'Facebook URL',
                        'vehdoc_twitter'   => 'Twitter/X URL',
                        'vehdoc_instagram' => 'Instagram URL',
                        'vehdoc_linkedin'  => 'LinkedIn URL',
                    );
                    foreach ($socials as $key => $label) :
                    ?>
                    <tr>
                        <th><label for="<?php echo $key; ?>"><?php echo esc_html($label); ?></label></th>
                        <td><input type="url" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo esc_attr(get_option($key)); ?>" class="regular-text"></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="vehdoc-admin-card">
                <h2><?php _e('Referral & Promo', 'vehdoc'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th><?php _e('Enable Referral System', 'vehdoc'); ?></th>
                        <td><label><input type="checkbox" name="vehdoc_enable_referral" value="1" <?php checked(get_option('vehdoc_enable_referral'), 1); ?>> <?php _e('Enable', 'vehdoc'); ?></label></td>
                    </tr>
                    <tr>
                        <th><label for="vehdoc_referral_bonus"><?php _e('Referral Bonus (₦)', 'vehdoc'); ?></label></th>
                        <td><input type="number" id="vehdoc_referral_bonus" name="vehdoc_referral_bonus" value="<?php echo esc_attr(get_option('vehdoc_referral_bonus', 1000)); ?>" class="regular-text" min="0" step="100"></td>
                    </tr>
                    <tr>
                        <th><?php _e('Enable Promo Codes', 'vehdoc'); ?></th>
                        <td><label><input type="checkbox" name="vehdoc_enable_promo" value="1" <?php checked(get_option('vehdoc_enable_promo'), 1); ?>> <?php _e('Enable', 'vehdoc'); ?></label></td>
                    </tr>
                </table>
            </div>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Payment Settings Page
 */
function vehdoc_payment_page() {
    ?>
    <div class="wrap vehdoc-admin-wrap">
        <h1><i class="dashicons dashicons-money-alt"></i> <?php _e('Payment Settings', 'vehdoc'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('vehdoc_settings'); ?>

            <div class="vehdoc-admin-card">
                <h2><?php _e('Payment Mode', 'vehdoc'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th><?php _e('Mode', 'vehdoc'); ?></th>
                        <td>
                            <select name="vehdoc_payment_mode">
                                <option value="test" <?php selected(get_option('vehdoc_payment_mode', 'test'), 'test'); ?>>Test Mode</option>
                                <option value="live" <?php selected(get_option('vehdoc_payment_mode'), 'live'); ?>>Live Mode</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="vehdoc-admin-card">
                <h2><?php _e('Paystack Settings', 'vehdoc'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th><label for="vehdoc_paystack_public_key"><?php _e('Public Key', 'vehdoc'); ?></label></th>
                        <td><input type="text" id="vehdoc_paystack_public_key" name="vehdoc_paystack_public_key" value="<?php echo esc_attr(get_option('vehdoc_paystack_public_key')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="vehdoc_paystack_secret_key"><?php _e('Secret Key', 'vehdoc'); ?></label></th>
                        <td><input type="password" id="vehdoc_paystack_secret_key" name="vehdoc_paystack_secret_key" value="<?php echo esc_attr(get_option('vehdoc_paystack_secret_key')); ?>" class="regular-text"></td>
                    </tr>
                </table>
            </div>

            <div class="vehdoc-admin-card">
                <h2><?php _e('Flutterwave Settings', 'vehdoc'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th><label for="vehdoc_flutterwave_public_key"><?php _e('Public Key', 'vehdoc'); ?></label></th>
                        <td><input type="text" id="vehdoc_flutterwave_public_key" name="vehdoc_flutterwave_public_key" value="<?php echo esc_attr(get_option('vehdoc_flutterwave_public_key')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="vehdoc_flutterwave_secret_key"><?php _e('Secret Key', 'vehdoc'); ?></label></th>
                        <td><input type="password" id="vehdoc_flutterwave_secret_key" name="vehdoc_flutterwave_secret_key" value="<?php echo esc_attr(get_option('vehdoc_flutterwave_secret_key')); ?>" class="regular-text"></td>
                    </tr>
                </table>
            </div>

            <div class="vehdoc-admin-card">
                <h2><?php _e('Delivery Fees', 'vehdoc'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th><label for="vehdoc_delivery_fee"><?php _e('Standard Delivery (₦)', 'vehdoc'); ?></label></th>
                        <td><input type="number" id="vehdoc_delivery_fee" name="vehdoc_delivery_fee" value="<?php echo esc_attr(get_option('vehdoc_delivery_fee', 3000)); ?>" class="regular-text" min="0" step="100"></td>
                    </tr>
                    <tr>
                        <th><label for="vehdoc_express_delivery_fee"><?php _e('Express Delivery (₦)', 'vehdoc'); ?></label></th>
                        <td><input type="number" id="vehdoc_express_delivery_fee" name="vehdoc_express_delivery_fee" value="<?php echo esc_attr(get_option('vehdoc_express_delivery_fee', 5000)); ?>" class="regular-text" min="0" step="100"></td>
                    </tr>
                </table>
            </div>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Notifications Settings Page
 */
function vehdoc_notifications_settings_page() {
    ?>
    <div class="wrap vehdoc-admin-wrap">
        <h1><i class="dashicons dashicons-bell"></i> <?php _e('Notification Settings', 'vehdoc'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('vehdoc_settings'); ?>

            <div class="vehdoc-admin-card">
                <h2><?php _e('SMS Settings', 'vehdoc'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th><label for="vehdoc_sms_api_key"><?php _e('SMS API Key', 'vehdoc'); ?></label></th>
                        <td><input type="password" id="vehdoc_sms_api_key" name="vehdoc_sms_api_key" value="<?php echo esc_attr(get_option('vehdoc_sms_api_key')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="vehdoc_sms_sender_id"><?php _e('Sender ID', 'vehdoc'); ?></label></th>
                        <td><input type="text" id="vehdoc_sms_sender_id" name="vehdoc_sms_sender_id" value="<?php echo esc_attr(get_option('vehdoc_sms_sender_id', 'VEHDOC')); ?>" class="regular-text"></td>
                    </tr>
                </table>
            </div>

            <div class="vehdoc-admin-card">
                <h2><?php _e('Reminder Settings', 'vehdoc'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th><?php _e('Reminder Days Before Expiry', 'vehdoc'); ?></th>
                        <td>
                            <label><input type="checkbox" name="vehdoc_reminder_days[]" value="30" <?php echo in_array('30', (array)get_option('vehdoc_reminder_days', array('30','14','7'))) ? 'checked' : ''; ?>> 30 days</label><br>
                            <label><input type="checkbox" name="vehdoc_reminder_days[]" value="14" <?php echo in_array('14', (array)get_option('vehdoc_reminder_days', array('30','14','7'))) ? 'checked' : ''; ?>> 14 days</label><br>
                            <label><input type="checkbox" name="vehdoc_reminder_days[]" value="7" <?php echo in_array('7', (array)get_option('vehdoc_reminder_days', array('30','14','7'))) ? 'checked' : ''; ?>> 7 days</label>
                        </td>
                    </tr>
                </table>
            </div>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Customizer
 */
function vehdoc_customize_register($wp_customize) {
    // Hero Section
    $wp_customize->add_section('vehdoc_hero', array(
        'title'    => __('Hero Section', 'vehdoc'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('vehdoc_hero_title', array('default' => 'Renew Your Vehicle Documents Without Leaving Your Home', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('vehdoc_hero_title', array('label' => __('Hero Title', 'vehdoc'), 'section' => 'vehdoc_hero', 'type' => 'text'));

    $wp_customize->add_setting('vehdoc_hero_subtitle', array('default' => 'Fast, secure and reliable vehicle documentation services with doorstep delivery across Nigeria.', 'sanitize_callback' => 'sanitize_textarea_field'));
    $wp_customize->add_control('vehdoc_hero_subtitle', array('label' => __('Hero Subtitle', 'vehdoc'), 'section' => 'vehdoc_hero', 'type' => 'textarea'));

    $wp_customize->add_setting('vehdoc_hero_bg', array('sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'vehdoc_hero_bg', array('label' => __('Hero Background', 'vehdoc'), 'section' => 'vehdoc_hero')));

    // CTA Section
    $wp_customize->add_setting('vehdoc_cta_text', array('default' => 'Ready to get your vehicle documents processed?', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('vehdoc_cta_text', array('label' => __('CTA Text', 'vehdoc'), 'section' => 'vehdoc_hero', 'type' => 'text'));
}
add_action('customize_register', 'vehdoc_customize_register');
