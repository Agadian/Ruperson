<?php
/**
 * Template Name: Services
 *
 * @package Vehdoc
 */

get_header();

$services = get_posts(array(
    'post_type'      => 'vehdoc_service',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
));

$service_icons = array(
    'fa-solid fa-id-card', 'fa-solid fa-file-lines', 'fa-solid fa-shield-halved',
    'fa-solid fa-right-left', 'fa-solid fa-hashtag', 'fa-solid fa-address-card',
    'fa-solid fa-window-maximize', 'fa-solid fa-car-burst', 'fa-solid fa-taxi',
    'fa-solid fa-truck-moving',
);
?>

<section class="page-hero">
    <div class="container">
        <h1 class="page-hero-title">Our Services</h1>
        <p class="page-hero-subtitle">Professional vehicle documentation services with doorstep delivery across Nigeria</p>
    </div>
</section>

<section class="services-page-section">
    <div class="container">
        <div class="services-page-grid">
            <?php foreach ($services as $index => $service) :
                $price           = get_post_meta($service->ID, '_vehdoc_service_price', true);
                $fast_track      = get_post_meta($service->ID, '_vehdoc_fast_track_price', true);
                $processing_time = get_post_meta($service->ID, '_vehdoc_service_time', true);
                $delivery_fee    = get_post_meta($service->ID, '_vehdoc_delivery_fee', true);
                $icon            = get_post_meta($service->ID, '_vehdoc_service_icon', true) ?: ($service_icons[$index] ?? 'fa-solid fa-file');
                $requirements    = get_post_meta($service->ID, '_vehdoc_requirements', true);
                $reqs_array      = $requirements ? array_filter(array_map('trim', explode("\n", $requirements))) : array();
            ?>
            <div class="service-detail-card animate-fade-up" id="service-<?php echo $service->ID; ?>">
                <div class="sdc-header">
                    <div class="sdc-icon"><i class="<?php echo esc_attr($icon); ?>"></i></div>
                    <div>
                        <h2><?php echo esc_html($service->post_title); ?></h2>
                        <span class="sdc-time"><i class="fa-regular fa-clock"></i> <?php echo esc_html($processing_time); ?></span>
                    </div>
                </div>
                <p class="sdc-description"><?php echo esc_html($service->post_content); ?></p>

                <?php if (!empty($reqs_array)) : ?>
                <div class="sdc-requirements">
                    <h4>Requirements</h4>
                    <ul>
                        <?php foreach ($reqs_array as $req) : ?>
                            <li><i class="fa-solid fa-check"></i> <?php echo esc_html($req); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="sdc-pricing">
                    <div class="pricing-option">
                        <span class="pricing-label">Standard</span>
                        <span class="pricing-amount">₦<?php echo number_format(floatval($price)); ?></span>
                    </div>
                    <?php if ($fast_track) : ?>
                    <div class="pricing-option fast-track">
                        <span class="pricing-label"><i class="fa-solid fa-bolt"></i> Fast-Track</span>
                        <span class="pricing-amount">₦<?php echo number_format(floatval($fast_track)); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($delivery_fee) : ?>
                    <div class="pricing-option delivery">
                        <span class="pricing-label"><i class="fa-solid fa-truck"></i> Delivery Fee</span>
                        <span class="pricing-amount">₦<?php echo number_format(floatval($delivery_fee)); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <a href="<?php echo is_user_logged_in() ? home_url('/dashboard/?tab=new-order&service=' . $service->ID) : home_url('/register/'); ?>" class="btn btn-primary btn-block">
                    Get Started <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
