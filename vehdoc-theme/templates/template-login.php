<?php
/**
 * Template Name: Login
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
                <h1>Welcome Back</h1>
                <p>Log in to manage your vehicle documents</p>
            </div>

            <form id="loginForm" class="auth-form">
                <div class="form-alert" id="loginAlert" style="display:none"></div>

                <div class="form-group">
                    <label for="login_email"><i class="fa-solid fa-envelope"></i> Email Address</label>
                    <input type="email" id="login_email" name="email" required placeholder="you@example.com" class="form-input">
                </div>

                <div class="form-group">
                    <label for="login_password"><i class="fa-solid fa-lock"></i> Password</label>
                    <div class="password-input">
                        <input type="password" id="login_password" name="password" required placeholder="Enter your password" class="form-input">
                        <button type="button" class="password-toggle" onclick="togglePassword('login_password')">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="#" id="forgotPasswordLink" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" id="loginBtn">
                    <span class="btn-text">Log In</span>
                    <span class="btn-loader" style="display:none"><i class="fa-solid fa-spinner fa-spin"></i></span>
                </button>
            </form>

            <!-- Forgot Password Form -->
            <form id="forgotPasswordForm" class="auth-form" style="display:none">
                <div class="form-alert" id="forgotAlert" style="display:none"></div>
                <div class="form-group">
                    <label for="forgot_email"><i class="fa-solid fa-envelope"></i> Email Address</label>
                    <input type="email" id="forgot_email" name="email" required placeholder="you@example.com" class="form-input">
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg">Send Reset Link</button>
                <button type="button" class="btn btn-ghost btn-block" id="backToLogin">Back to Login</button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="<?php echo home_url('/register/'); ?>">Sign up</a></p>
            </div>
        </div>

        <div class="auth-visual">
            <div class="auth-visual-content">
                <h2>Manage Your Vehicle Documents Online</h2>
                <p>Process, track, and renew your vehicle documentation from anywhere in Nigeria.</p>
                <div class="auth-features">
                    <div class="auth-feature"><i class="fa-solid fa-check-circle"></i> Fast processing</div>
                    <div class="auth-feature"><i class="fa-solid fa-check-circle"></i> Secure payments</div>
                    <div class="auth-feature"><i class="fa-solid fa-check-circle"></i> Doorstep delivery</div>
                    <div class="auth-feature"><i class="fa-solid fa-check-circle"></i> Real-time tracking</div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
