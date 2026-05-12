<?php
/**
 * Template Name: Register
 *
 * @package Vehdoc
 */

if (is_user_logged_in()) {
    wp_redirect(home_url('/dashboard/'));
    exit;
}

get_header(); ?>

<section class="auth-section">
    <div class="auth-container">
        <div class="auth-card animate-fade-in">
            <div class="auth-header">
                <a href="<?php echo home_url('/'); ?>" class="auth-logo">
                    <span class="logo-text">Veh<span class="logo-accent">doc</span></span>
                </a>
                <h1>Create Your Account</h1>
                <p>Start processing your vehicle documents today</p>
            </div>

            <form id="registerForm" class="auth-form">
                <div class="form-alert" id="registerAlert" style="display:none"></div>

                <div class="form-group">
                    <label for="reg_name"><i class="fa-solid fa-user"></i> Full Name</label>
                    <input type="text" id="reg_name" name="full_name" required placeholder="Enter your full name" class="form-input">
                </div>

                <div class="form-group">
                    <label for="reg_email"><i class="fa-solid fa-envelope"></i> Email Address</label>
                    <input type="email" id="reg_email" name="email" required placeholder="you@example.com" class="form-input">
                </div>

                <div class="form-group">
                    <label for="reg_phone"><i class="fa-solid fa-phone"></i> Phone Number</label>
                    <input type="tel" id="reg_phone" name="phone" required placeholder="+234..." class="form-input">
                </div>

                <div class="form-group">
                    <label for="reg_password"><i class="fa-solid fa-lock"></i> Password</label>
                    <div class="password-input">
                        <input type="password" id="reg_password" name="password" required placeholder="Min. 8 characters" class="form-input" minlength="8">
                        <button type="button" class="password-toggle" onclick="togglePassword('reg_password')">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="passwordStrength"></div>
                </div>

                <div class="form-group">
                    <label for="reg_confirm"><i class="fa-solid fa-lock"></i> Confirm Password</label>
                    <div class="password-input">
                        <input type="password" id="reg_confirm" name="confirm_password" required placeholder="Confirm your password" class="form-input">
                        <button type="button" class="password-toggle" onclick="togglePassword('reg_confirm')">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <?php if (isset($_GET['ref'])) : ?>
                    <input type="hidden" name="referral_code" value="<?php echo esc_attr($_GET['ref']); ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="agree_terms" required>
                        I agree to the <a href="<?php echo home_url('/terms/'); ?>" target="_blank">Terms of Service</a> and <a href="<?php echo home_url('/privacy-policy/'); ?>" target="_blank">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" id="registerBtn">
                    <span class="btn-text">Create Account</span>
                    <span class="btn-loader" style="display:none"><i class="fa-solid fa-spinner fa-spin"></i></span>
                </button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="<?php echo home_url('/login/'); ?>">Log in</a></p>
            </div>
        </div>

        <div class="auth-visual">
            <div class="auth-visual-content">
                <h2>Join 10,000+ Vehicle Owners</h2>
                <p>Create your free account and start managing your vehicle documents with ease.</p>
                <div class="auth-features">
                    <div class="auth-feature"><i class="fa-solid fa-check-circle"></i> Free to register</div>
                    <div class="auth-feature"><i class="fa-solid fa-check-circle"></i> Multiple vehicles</div>
                    <div class="auth-feature"><i class="fa-solid fa-check-circle"></i> Renewal reminders</div>
                    <div class="auth-feature"><i class="fa-solid fa-check-circle"></i> 24/7 support</div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
