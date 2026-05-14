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

$default_requirements = array(
    'Vehicle Licence Renewal' => array(
        'Current vehicle licence (expired or expiring)',
        'Proof of ownership certificate',
        'Valid means of identification (NIN, driver\'s licence, or intl. passport)',
        'Vehicle insurance certificate',
        'Passport photograph (white background)',
    ),
    'Proof of Ownership' => array(
        'Customs duty papers or purchase receipt',
        'Previous proof of ownership (if applicable)',
        'Valid ID of vehicle owner',
        'Vehicle particulars (chassis & engine number)',
        'Passport photograph (white background)',
    ),
    'Road Worthiness Certificate' => array(
        'Valid vehicle licence',
        'Vehicle insurance certificate',
        'Proof of ownership',
        'Passport photograph (white background)',
    ),
    'Change of Ownership' => array(
        'Current proof of ownership from seller',
        'Sales agreement / transfer letter',
        'Valid ID of both buyer and seller',
        'Vehicle licence',
        'Passport photographs of buyer and seller',
    ),
    'Plate Number Processing' => array(
        'Customs duty papers',
        'Proof of ownership',
        'Valid means of identification',
        'Vehicle insurance',
        'Passport photograph (white background)',
    ),
    "Driver's Licence Renewal" => array(
        'Expired or expiring driver\'s licence',
        'Valid means of identification',
        'Passport photograph (white background)',
        'Certificate of eye test',
    ),
    'Tinted Permit Processing' => array(
        'Vehicle licence',
        'Proof of ownership',
        'Valid means of identification',
        'Reason for tinting (medical or security)',
        'Passport photograph (white background)',
    ),
    'Vehicle Insurance Processing' => array(
        'Vehicle licence',
        'Proof of ownership',
        'Valid means of identification',
        'Vehicle details (make, model, year, chassis number)',
    ),
    'Hackney Permit' => array(
        'Vehicle licence',
        'Proof of ownership',
        'Driver\'s licence of designated driver',
        'Vehicle insurance certificate',
        'Road worthiness certificate',
        'Passport photograph (white background)',
    ),
    'Fleet Registration Services' => array(
        'Company registration documents (CAC)',
        'Fleet vehicle list with details',
        'Proof of ownership for each vehicle',
        'Insurance certificates for all vehicles',
        'Company letterhead authorization',
        'Valid ID of authorized representative',
    ),
);
?>

<section class="page-hero">
    <div class="container">
        <span class="page-hero-badge"><i class="fa-solid fa-clipboard-list"></i> Our Services</span>
        <h1 class="page-hero-title">Vehicle Documentation Services</h1>
        <p class="page-hero-subtitle">Professional handling of all your vehicle documentation needs with transparent pricing, fast processing, and doorstep delivery across Nigeria.</p>
    </div>
</section>

<!-- Service Highlights -->
<section class="services-highlights">
    <div class="container">
        <div class="highlights-grid">
            <div class="highlight-item">
                <i class="fa-solid fa-shield-halved"></i>
                <strong>100% Secure</strong>
                <span>Encrypted payments & documents</span>
            </div>
            <div class="highlight-item">
                <i class="fa-solid fa-truck-fast"></i>
                <strong>Doorstep Delivery</strong>
                <span>All 36 states + FCT</span>
            </div>
            <div class="highlight-item">
                <i class="fa-solid fa-bolt"></i>
                <strong>Fast-Track Available</strong>
                <span>Priority processing option</span>
            </div>
            <div class="highlight-item">
                <i class="fa-solid fa-naira-sign"></i>
                <strong>Pay in Naira</strong>
                <span>Card, bank transfer, USSD</span>
            </div>
        </div>
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
                if (empty($reqs_array) && isset($default_requirements[$service->post_title])) {
                    $reqs_array = $default_requirements[$service->post_title];
                }
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
                    <h4><i class="fa-solid fa-list-check"></i> Requirements</h4>
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

<!-- Process Reminder -->
<section class="services-process-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">How It Works</span>
            <h2 class="section-title">Simple 4-Step Process</h2>
        </div>
        <div class="steps-grid">
            <div class="step-card animate-fade-up">
                <div class="step-number">01</div>
                <div class="step-icon"><i class="fa-solid fa-hand-pointer"></i></div>
                <h3>Choose a Service</h3>
                <p>Select the vehicle documentation service you need from the options above.</p>
            </div>
            <div class="step-connector"><i class="fa-solid fa-arrow-right"></i></div>
            <div class="step-card animate-fade-up">
                <div class="step-number">02</div>
                <div class="step-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                <h3>Upload Documents</h3>
                <p>Upload the required documents in PDF, JPG, or PNG format (max 10MB each).</p>
            </div>
            <div class="step-connector"><i class="fa-solid fa-arrow-right"></i></div>
            <div class="step-card animate-fade-up">
                <div class="step-number">03</div>
                <div class="step-icon"><i class="fa-solid fa-credit-card"></i></div>
                <h3>Make Payment</h3>
                <p>Pay securely in Naira via Paystack or Flutterwave (card, bank, or USSD).</p>
            </div>
            <div class="step-connector"><i class="fa-solid fa-arrow-right"></i></div>
            <div class="step-card animate-fade-up">
                <div class="step-number">04</div>
                <div class="step-icon"><i class="fa-solid fa-truck-fast"></i></div>
                <h3>Receive Documents</h3>
                <p>Track your order and receive processed documents at your doorstep.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content animate-fade-in">
            <h2>Ready to Get Started?</h2>
            <p>Create a free account and process your first vehicle document in minutes. No office visits required.</p>
            <div class="cta-buttons">
                <a href="<?php echo home_url('/register/'); ?>" class="btn btn-white btn-lg">
                    <i class="fa-solid fa-rocket"></i> Create Free Account
                </a>
                <a href="<?php echo home_url('/contact-us/'); ?>" class="btn btn-outline-white btn-lg">
                    <i class="fa-solid fa-headset"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
