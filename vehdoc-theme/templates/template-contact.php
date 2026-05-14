<?php
/**
 * Template Name: Contact Us
 *
 * @package Vehdoc
 */

get_header(); ?>

<section class="page-hero">
    <div class="container">
        <span class="page-hero-badge"><i class="fa-solid fa-envelope"></i> Contact Us</span>
        <h1 class="page-hero-title">Get in Touch</h1>
        <p class="page-hero-subtitle">Have questions about our services? Need help with your order? Our team is ready to assist you.</p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Info -->
            <div class="contact-info animate-fade-up">
                <h2>We'd Love to Hear From You</h2>
                <p>Whether you have a question about our services, pricing, processing times, or anything else — our team is here to help.</p>

                <div class="contact-cards">
                    <div class="contact-card">
                        <div class="contact-card-icon"><i class="fa-solid fa-phone"></i></div>
                        <div>
                            <h4>Call Us</h4>
                            <p><?php echo esc_html(get_option('vehdoc_company_phone', '+234 800 VEHDOC')); ?></p>
                            <span>Mon - Sat, 8am - 6pm WAT</span>
                        </div>
                    </div>
                    <div class="contact-card">
                        <div class="contact-card-icon"><i class="fa-solid fa-envelope"></i></div>
                        <div>
                            <h4>Email Us</h4>
                            <p><?php echo esc_html(get_option('vehdoc_company_email', 'support@vehdoc.com')); ?></p>
                            <span>We respond within 2 hours</span>
                        </div>
                    </div>
                    <div class="contact-card">
                        <div class="contact-card-icon"><i class="fa-brands fa-whatsapp"></i></div>
                        <div>
                            <h4>WhatsApp</h4>
                            <p><?php echo esc_html(get_option('vehdoc_whatsapp_number', '+234 800 VEHDOC')); ?></p>
                            <span>Instant messaging support</span>
                        </div>
                    </div>
                    <div class="contact-card">
                        <div class="contact-card-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div>
                            <h4>Visit Us</h4>
                            <p><?php echo esc_html(get_option('vehdoc_company_address', 'Lagos, Nigeria')); ?></p>
                            <span>By appointment only</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-wrapper animate-fade-up">
                <div class="contact-form-card">
                    <h3>Send Us a Message</h3>
                    <p>Fill out the form below and we'll get back to you as soon as possible.</p>

                    <form id="contactForm" class="contact-form">
                        <div class="form-alert" id="contactAlert" style="display:none"></div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="contact_name"><i class="fa-solid fa-user"></i> Full Name</label>
                                <input type="text" id="contact_name" name="name" required placeholder="Your full name" class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="contact_email"><i class="fa-solid fa-envelope"></i> Email Address</label>
                                <input type="email" id="contact_email" name="email" required placeholder="you@example.com" class="form-input">
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="contact_phone"><i class="fa-solid fa-phone"></i> Phone Number</label>
                                <input type="tel" id="contact_phone" name="phone" placeholder="+234..." class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="contact_subject"><i class="fa-solid fa-tag"></i> Subject</label>
                                <select id="contact_subject" name="subject" class="form-input" required>
                                    <option value="">Select a topic</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="order">Order Support</option>
                                    <option value="payment">Payment Issue</option>
                                    <option value="delivery">Delivery Question</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contact_message"><i class="fa-solid fa-message"></i> Message</label>
                            <textarea id="contact_message" name="message" required placeholder="Tell us how we can help you..." class="form-input" rows="5"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" id="contactBtn">
                            <span class="btn-text"><i class="fa-solid fa-paper-plane"></i> Send Message</span>
                            <span class="btn-loader" style="display:none"><i class="fa-solid fa-spinner fa-spin"></i></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Quick Links -->
<section class="contact-faq-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Quick Answers</span>
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-subtitle">Find quick answers to common questions before reaching out</p>
        </div>
        <div class="contact-faq-grid">
            <div class="contact-faq-card animate-fade-up">
                <i class="fa-solid fa-clock"></i>
                <h4>How long does processing take?</h4>
                <p>Most documents are processed within 3-14 business days. Fast-track options are available for quicker turnaround.</p>
            </div>
            <div class="contact-faq-card animate-fade-up">
                <i class="fa-solid fa-credit-card"></i>
                <h4>What payment methods do you accept?</h4>
                <p>We accept card payments, bank transfers, and USSD via Paystack and Flutterwave. All payments are in Nigerian Naira (₦).</p>
            </div>
            <div class="contact-faq-card animate-fade-up">
                <i class="fa-solid fa-truck"></i>
                <h4>Do you deliver nationwide?</h4>
                <p>Yes, we deliver to all 36 states and the FCT. Express delivery is available in Lagos, Abuja, and Port Harcourt.</p>
            </div>
            <div class="contact-faq-card animate-fade-up">
                <i class="fa-solid fa-rotate-left"></i>
                <h4>What's your refund policy?</h4>
                <p>If your application is rejected due to our error, we'll reprocess it for free. Full refunds are available for unprocessed orders.</p>
            </div>
        </div>
        <div class="text-center" style="margin-top: 2rem;">
            <a href="<?php echo home_url('/#faq'); ?>" class="btn btn-outline btn-lg">View All FAQs <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
