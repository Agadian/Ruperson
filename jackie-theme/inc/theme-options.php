<?php
/**
 * Theme Options - Customizer Settings
 */

if (!defined('ABSPATH')) exit;

function jackie_customize_register($wp_customize) {

    /* ---------- Company Info Section ---------- */
    $wp_customize->add_section('jackie_company', array(
        'title'    => __('Company Info', 'jackie-theme'),
        'priority' => 30,
    ));

    // Phone
    $wp_customize->add_setting('jackie_phone', array('default' => '+234 800 000 0000', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('jackie_phone', array('label' => 'Phone Number', 'section' => 'jackie_company', 'type' => 'text'));

    // Phone 2
    $wp_customize->add_setting('jackie_phone2', array('default' => '+234 901 234 5678', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('jackie_phone2', array('label' => 'Phone Number 2', 'section' => 'jackie_company', 'type' => 'text'));

    // Email
    $wp_customize->add_setting('jackie_email', array('default' => 'hello@jackiecreative.com', 'sanitize_callback' => 'sanitize_email'));
    $wp_customize->add_control('jackie_email', array('label' => 'Email', 'section' => 'jackie_company', 'type' => 'email'));

    // Email 2
    $wp_customize->add_setting('jackie_email2', array('default' => 'info@jackiecreative.com', 'sanitize_callback' => 'sanitize_email'));
    $wp_customize->add_control('jackie_email2', array('label' => 'Email 2', 'section' => 'jackie_company', 'type' => 'email'));

    // Address
    $wp_customize->add_setting('jackie_address', array('default' => '123 Creative Avenue, Victoria Island, Lagos, Nigeria', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('jackie_address', array('label' => 'Address', 'section' => 'jackie_company', 'type' => 'textarea'));

    // WhatsApp Number
    $wp_customize->add_setting('jackie_whatsapp', array('default' => '2348000000000', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('jackie_whatsapp', array('label' => 'WhatsApp Number (with country code, no +)', 'section' => 'jackie_company', 'type' => 'text'));

    // Google Maps Embed URL
    $wp_customize->add_setting('jackie_map_embed', array(
        'default' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.7293958152024!2d3.4226085!3d6.4280556!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103bf53aec4dd92d%3A0x5e34fe6b8d86e3e5!2sVictoria%20Island%2C%20Lagos!5e0!3m2!1sen!2sng!4v1700000000000!5m2!1sen!2sng',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('jackie_map_embed', array('label' => 'Google Maps Embed URL', 'section' => 'jackie_company', 'type' => 'url'));

    /* ---------- Social Media Section ---------- */
    $wp_customize->add_section('jackie_social', array(
        'title'    => __('Social Media Links', 'jackie-theme'),
        'priority' => 35,
    ));

    $socials = array('facebook', 'instagram', 'twitter', 'linkedin', 'behance', 'tiktok');
    foreach ($socials as $social) {
        $wp_customize->add_setting('jackie_' . $social, array('default' => '#', 'sanitize_callback' => 'esc_url_raw'));
        $wp_customize->add_control('jackie_' . $social, array('label' => ucfirst($social) . ' URL', 'section' => 'jackie_social', 'type' => 'url'));
    }

    /* ---------- Statistics Section ---------- */
    $wp_customize->add_section('jackie_stats', array(
        'title'    => __('Homepage Statistics', 'jackie-theme'),
        'priority' => 40,
    ));

    $wp_customize->add_setting('jackie_stat_projects', array('default' => '500', 'sanitize_callback' => 'absint'));
    $wp_customize->add_control('jackie_stat_projects', array('label' => 'Projects Completed', 'section' => 'jackie_stats', 'type' => 'number'));

    $wp_customize->add_setting('jackie_stat_clients', array('default' => '350', 'sanitize_callback' => 'absint'));
    $wp_customize->add_control('jackie_stat_clients', array('label' => 'Happy Clients', 'section' => 'jackie_stats', 'type' => 'number'));

    $wp_customize->add_setting('jackie_stat_years', array('default' => '8', 'sanitize_callback' => 'absint'));
    $wp_customize->add_control('jackie_stat_years', array('label' => 'Years Experience', 'section' => 'jackie_stats', 'type' => 'number'));

    /* ---------- Business Hours ---------- */
    $wp_customize->add_section('jackie_hours', array(
        'title'    => __('Business Hours', 'jackie-theme'),
        'priority' => 45,
    ));

    $wp_customize->add_setting('jackie_hours_weekday', array('default' => '9:00 AM - 6:00 PM', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('jackie_hours_weekday', array('label' => 'Monday - Friday', 'section' => 'jackie_hours', 'type' => 'text'));

    $wp_customize->add_setting('jackie_hours_saturday', array('default' => '10:00 AM - 4:00 PM', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('jackie_hours_saturday', array('label' => 'Saturday', 'section' => 'jackie_hours', 'type' => 'text'));

    $wp_customize->add_setting('jackie_hours_sunday', array('default' => 'Closed', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('jackie_hours_sunday', array('label' => 'Sunday', 'section' => 'jackie_hours', 'type' => 'text'));
}
add_action('customize_register', 'jackie_customize_register');

/* ---------- Helper function to get theme mod ---------- */
function jackie_get($key, $default = '') {
    return get_theme_mod('jackie_' . $key, $default);
}
