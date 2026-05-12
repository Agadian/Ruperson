<?php
/**
 * Vehdoc Landing Page
 *
 * @package Vehdoc
 */

get_header();

$hero_title    = get_theme_mod('vehdoc_hero_title', 'Renew Your Vehicle Documents Without Leaving Your Home');
$hero_subtitle = get_theme_mod('vehdoc_hero_subtitle', 'Fast, secure and reliable vehicle documentation services with doorstep delivery across Nigeria.');
$hero_bg       = get_theme_mod('vehdoc_hero_bg', '');

$services = get_posts(array(
    'post_type'      => 'vehdoc_service',
    'posts_per_page' => 10,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
));

$service_icons = array(
    'fa-solid fa-id-card',
    'fa-solid fa-file-certificate',
    'fa-solid fa-shield-check',
    'fa-solid fa-right-left',
    'fa-solid fa-hashtag',
    'fa-solid fa-address-card',
    'fa-solid fa-window-maximize',
    'fa-solid fa-car-burst',
    'fa-solid fa-taxi',
    'fa-solid fa-truck-moving',
);
?>

<!-- Hero Section -->
<section class="hero-section" id="hero" <?php echo $hero_bg ? 'style="background-image:url(' . esc_url($hero_bg) . ')"' : ''; ?>>
    <div class="hero-bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-badge animate-fade-in">
                    <i class="fa-solid fa-shield-halved"></i>
                    Trusted by 10,000+ vehicle owners across Nigeria
                </div>
                <h1 class="hero-title animate-fade-in"><?php echo esc_html($hero_title); ?></h1>
                <p class="hero-subtitle animate-fade-in"><?php echo esc_html($hero_subtitle); ?></p>
                <div class="hero-cta animate-fade-in">
                    <a href="<?php echo home_url('/register/'); ?>" class="btn btn-primary btn-lg">
                        <i class="fa-solid fa-rocket"></i> Get Started
                    </a>
                    <a href="<?php echo home_url('/services/'); ?>" class="btn btn-outline-white btn-lg">
                        <i class="fa-solid fa-rotate"></i> Renew Now
                    </a>
                </div>
                <div class="hero-trust animate-fade-in">
                    <div class="trust-item"><i class="fa-solid fa-lock"></i> Secure Payments</div>
                    <div class="trust-item"><i class="fa-solid fa-truck-fast"></i> Doorstep Delivery</div>
                    <div class="trust-item"><i class="fa-solid fa-headset"></i> 24/7 Support</div>
                </div>
            </div>
            <div class="hero-visual animate-slide-right">
                <div class="hero-card">
                    <div class="card-header">
                        <div class="card-dot green"></div>
                        <div class="card-dot yellow"></div>
                        <div class="card-dot red"></div>
                    </div>
                    <div class="card-body">
                        <div class="mock-status">
                            <div class="status-icon"><i class="fa-solid fa-circle-check"></i></div>
                            <div class="status-text">
                                <strong>Vehicle Licence Renewed</strong>
                                <span>Ready for doorstep delivery</span>
                            </div>
                        </div>
                        <div class="mock-progress">
                            <div class="progress-step done"><i class="fa-solid fa-check"></i> Submitted</div>
                            <div class="progress-step done"><i class="fa-solid fa-check"></i> Processing</div>
                            <div class="progress-step done"><i class="fa-solid fa-check"></i> Approved</div>
                            <div class="progress-step active"><i class="fa-solid fa-truck"></i> Delivery</div>
                        </div>
                        <div class="mock-vehicle">
                            <i class="fa-solid fa-car"></i>
                            <div>
                                <strong>Toyota Camry 2022</strong>
                                <span>LAG-234-XY</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item animate-count">
                <span class="stat-number" data-count="10000">0</span>
                <span class="stat-suffix">+</span>
                <span class="stat-label">Documents Processed</span>
            </div>
            <div class="stat-item animate-count">
                <span class="stat-number" data-count="5000">0</span>
                <span class="stat-suffix">+</span>
                <span class="stat-label">Happy Customers</span>
            </div>
            <div class="stat-item animate-count">
                <span class="stat-number" data-count="37">0</span>
                <span class="stat-suffix"></span>
                <span class="stat-label">States Covered</span>
            </div>
            <div class="stat-item animate-count">
                <span class="stat-number" data-count="98">0</span>
                <span class="stat-suffix">%</span>
                <span class="stat-label">Success Rate</span>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works" id="how-it-works">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Simple Process</span>
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Get your vehicle documents processed in 4 easy steps</p>
        </div>
        <div class="steps-grid">
            <div class="step-card animate-fade-up">
                <div class="step-number">01</div>
                <div class="step-icon"><i class="fa-solid fa-user-plus"></i></div>
                <h3>Create Account</h3>
                <p>Sign up in seconds with your email and phone number. Add your vehicle details.</p>
            </div>
            <div class="step-connector"><i class="fa-solid fa-arrow-right"></i></div>
            <div class="step-card animate-fade-up" style="animation-delay: 0.1s">
                <div class="step-number">02</div>
                <div class="step-icon"><i class="fa-solid fa-list-check"></i></div>
                <h3>Select Service</h3>
                <p>Choose the vehicle document service you need and upload required documents.</p>
            </div>
            <div class="step-connector"><i class="fa-solid fa-arrow-right"></i></div>
            <div class="step-card animate-fade-up" style="animation-delay: 0.2s">
                <div class="step-number">03</div>
                <div class="step-icon"><i class="fa-solid fa-credit-card"></i></div>
                <h3>Pay Securely</h3>
                <p>Pay online in Naira using your preferred method — card, bank transfer, or USSD.</p>
            </div>
            <div class="step-connector"><i class="fa-solid fa-arrow-right"></i></div>
            <div class="step-card animate-fade-up" style="animation-delay: 0.3s">
                <div class="step-number">04</div>
                <div class="step-icon"><i class="fa-solid fa-truck-fast"></i></div>
                <h3>Doorstep Delivery</h3>
                <p>Track your order in real-time and receive your documents at your doorstep.</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section" id="services">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Our Services</span>
            <h2 class="section-title">Vehicle Documentation Services</h2>
            <p class="section-subtitle">Professional handling of all your vehicle documentation needs</p>
        </div>
        <div class="services-grid">
            <?php foreach ($services as $index => $service) :
                $price           = get_post_meta($service->ID, '_vehdoc_service_price', true);
                $processing_time = get_post_meta($service->ID, '_vehdoc_service_time', true);
                $icon            = get_post_meta($service->ID, '_vehdoc_service_icon', true) ?: ($service_icons[$index] ?? 'fa-solid fa-file');
                $fast_track      = get_post_meta($service->ID, '_vehdoc_fast_track_price', true);
            ?>
            <div class="service-card animate-fade-up" style="animation-delay: <?php echo $index * 0.05; ?>s">
                <div class="service-icon"><i class="<?php echo esc_attr($icon); ?>"></i></div>
                <h3 class="service-title"><?php echo esc_html($service->post_title); ?></h3>
                <p class="service-desc"><?php echo esc_html(wp_trim_words($service->post_content, 20)); ?></p>
                <div class="service-meta">
                    <span class="service-price">₦<?php echo number_format(floatval($price)); ?></span>
                    <span class="service-time"><i class="fa-regular fa-clock"></i> <?php echo esc_html($processing_time); ?></span>
                </div>
                <?php if ($fast_track) : ?>
                <div class="service-fast-track">
                    <i class="fa-solid fa-bolt"></i> Fast-track: ₦<?php echo number_format(floatval($fast_track)); ?>
                </div>
                <?php endif; ?>
                <a href="<?php echo is_user_logged_in() ? home_url('/dashboard/?tab=new-order&service=' . $service->ID) : home_url('/register/'); ?>" class="btn btn-primary btn-block">
                    Get Started <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="why-choose-us" id="why-us">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Why Vehdoc</span>
            <h2 class="section-title">Why Choose Us</h2>
            <p class="section-subtitle">We make vehicle documentation simple, fast, and stress-free</p>
        </div>
        <div class="features-grid">
            <div class="feature-card animate-fade-up">
                <div class="feature-icon"><i class="fa-solid fa-bolt-lightning"></i></div>
                <h3>Lightning Fast</h3>
                <p>Get your documents processed in record time with our fast-track option available.</p>
            </div>
            <div class="feature-card animate-fade-up">
                <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <h3>100% Secure</h3>
                <p>Your documents and payments are protected with bank-level encryption and security.</p>
            </div>
            <div class="feature-card animate-fade-up">
                <div class="feature-icon"><i class="fa-solid fa-truck-fast"></i></div>
                <h3>Doorstep Delivery</h3>
                <p>Receive your processed documents right at your doorstep — no office visits needed.</p>
            </div>
            <div class="feature-card animate-fade-up">
                <div class="feature-icon"><i class="fa-solid fa-eye"></i></div>
                <h3>Real-Time Tracking</h3>
                <p>Track your document processing status in real-time from your dashboard.</p>
            </div>
            <div class="feature-card animate-fade-up">
                <div class="feature-icon"><i class="fa-solid fa-bell"></i></div>
                <h3>Renewal Reminders</h3>
                <p>Never miss an expiry date — get automatic reminders before your documents expire.</p>
            </div>
            <div class="feature-card animate-fade-up">
                <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
                <h3>Expert Support</h3>
                <p>Our team of experts is available via WhatsApp, email, and phone to assist you.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials-section" id="testimonials">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Testimonials</span>
            <h2 class="section-title">What Our Customers Say</h2>
            <p class="section-subtitle">Join thousands of satisfied vehicle owners across Nigeria</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card animate-fade-up">
                <div class="testimonial-stars">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <p class="testimonial-text">"Vehdoc saved me so much time and stress. I renewed my vehicle licence without leaving my office. The documents were delivered to my doorstep in 3 days!"</p>
                <div class="testimonial-author">
                    <div class="author-avatar"><i class="fa-solid fa-user"></i></div>
                    <div>
                        <strong>Adebayo Johnson</strong>
                        <span>Lagos, Nigeria</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card animate-fade-up" style="animation-delay: 0.1s">
                <div class="testimonial-stars">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <p class="testimonial-text">"As a fleet manager with 20+ vehicles, Vehdoc has been a game-changer. They handle all our documentation and renewals seamlessly. Highly recommended!"</p>
                <div class="testimonial-author">
                    <div class="author-avatar"><i class="fa-solid fa-user"></i></div>
                    <div>
                        <strong>Chidinma Okafor</strong>
                        <span>Abuja, Nigeria</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card animate-fade-up" style="animation-delay: 0.2s">
                <div class="testimonial-stars">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <p class="testimonial-text">"The tracking feature is amazing! I could see exactly where my documents were in the process. Payment was easy and the delivery was right on time."</p>
                <div class="testimonial-author">
                    <div class="author-avatar"><i class="fa-solid fa-user"></i></div>
                    <div>
                        <strong>Emeka Nwosu</strong>
                        <span>Port Harcourt, Nigeria</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section" id="faq">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">FAQ</span>
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-subtitle">Got questions? We've got answers.</p>
        </div>
        <div class="faq-list">
            <div class="faq-item animate-fade-up">
                <button class="faq-question">
                    <span>How long does the process take?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Processing times vary by service. Most documents are processed within 3-14 business days. You can opt for fast-track processing for quicker turnaround. You'll receive real-time updates throughout the process.</p>
                </div>
            </div>
            <div class="faq-item animate-fade-up">
                <button class="faq-question">
                    <span>What documents do I need to upload?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Requirements vary by service. Typically you'll need: old vehicle papers, proof of ownership, valid ID card, and passport photographs. Each service page lists specific requirements.</p>
                </div>
            </div>
            <div class="faq-item animate-fade-up">
                <button class="faq-question">
                    <span>Is my payment secure?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Absolutely. We use Paystack and Flutterwave — Nigeria's most trusted payment processors. All transactions are encrypted and secured. You'll receive an instant digital receipt.</p>
                </div>
            </div>
            <div class="faq-item animate-fade-up">
                <button class="faq-question">
                    <span>Do you deliver to all states?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Yes, we deliver to all 36 states and the FCT. Express delivery is available in Lagos, Abuja, and Port Harcourt for same-day or next-day delivery.</p>
                </div>
            </div>
            <div class="faq-item animate-fade-up">
                <button class="faq-question">
                    <span>What if my application is rejected?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>In the rare case of a rejection, we'll notify you immediately with the reason and guide you on next steps. If the issue is on our end, we'll reprocess at no extra cost.</p>
                </div>
            </div>
            <div class="faq-item animate-fade-up">
                <button class="faq-question">
                    <span>Can I track my order?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Yes! Once you place an order, you can track its progress in real-time from your dashboard. You'll also receive email and SMS updates at each stage.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile App Mockup -->
<section class="mobile-app-section">
    <div class="container">
        <div class="app-content">
            <div class="app-text animate-fade-in">
                <span class="section-badge">Mobile App</span>
                <h2 class="section-title">Manage Your Documents On The Go</h2>
                <p>Download the Vehdoc mobile app for an even better experience. Track orders, upload documents, make payments, and receive notifications — all from your phone.</p>
                <div class="app-features">
                    <div class="app-feature"><i class="fa-solid fa-check-circle"></i> Real-time tracking</div>
                    <div class="app-feature"><i class="fa-solid fa-check-circle"></i> Push notifications</div>
                    <div class="app-feature"><i class="fa-solid fa-check-circle"></i> Document scanner</div>
                    <div class="app-feature"><i class="fa-solid fa-check-circle"></i> Instant payments</div>
                </div>
                <div class="app-buttons">
                    <a href="#" class="app-store-btn">
                        <i class="fa-brands fa-apple"></i>
                        <div><span>Download on the</span><strong>App Store</strong></div>
                    </a>
                    <a href="#" class="app-store-btn">
                        <i class="fa-brands fa-google-play"></i>
                        <div><span>Get it on</span><strong>Google Play</strong></div>
                    </a>
                </div>
            </div>
            <div class="app-mockup animate-slide-right">
                <div class="phone-frame">
                    <div class="phone-screen">
                        <div class="mock-app-header">
                            <span class="logo-text-sm">Veh<span class="logo-accent">doc</span></span>
                            <i class="fa-solid fa-bell"></i>
                        </div>
                        <div class="mock-app-greeting">
                            <span>Welcome back,</span>
                            <strong>Adebayo</strong>
                        </div>
                        <div class="mock-app-stats">
                            <div class="mini-stat"><strong>3</strong><span>Vehicles</span></div>
                            <div class="mini-stat"><strong>2</strong><span>Active</span></div>
                            <div class="mini-stat"><strong>7</strong><span>Completed</span></div>
                        </div>
                        <div class="mock-app-card">
                            <div class="mini-card-header"><strong>Recent Order</strong><span class="mini-badge">Processing</span></div>
                            <div class="mini-card-body">Vehicle Licence Renewal<br><small>Toyota Camry — LAG-234-XY</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section" id="contact">
    <div class="container">
        <div class="cta-content animate-fade-in">
            <h2><?php echo esc_html(get_theme_mod('vehdoc_cta_text', 'Ready to get your vehicle documents processed?')); ?></h2>
            <p>Join thousands of vehicle owners who trust Vehdoc for fast, secure, and hassle-free document processing.</p>
            <div class="cta-buttons">
                <a href="<?php echo home_url('/register/'); ?>" class="btn btn-white btn-lg">
                    <i class="fa-solid fa-rocket"></i> Get Started Now
                </a>
                <a href="<?php echo 'https://wa.me/' . preg_replace('/[^0-9]/', '', get_option('vehdoc_whatsapp_number', '')); ?>" target="_blank" class="btn btn-outline-white btn-lg">
                    <i class="fa-brands fa-whatsapp"></i> Chat With Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
