/**
 * Vehdoc Main JavaScript
 *
 * @package Vehdoc
 */

(function($) {
    'use strict';

    // Preloader
    window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            setTimeout(function() {
                preloader.classList.add('hidden');
            }, 500);
        }
    });

    // Scroll Progress Bar
    window.addEventListener('scroll', function() {
        const scrollProgress = document.getElementById('scrollProgress');
        if (scrollProgress) {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            scrollProgress.style.width = scrollPercent + '%';
        }
    });

    // Header Scroll Effect
    const header = document.getElementById('mainHeader');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // Mobile Menu
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
            if (mobileOverlay) mobileOverlay.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        });

        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function() {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                mobileOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
    }

    // Theme Toggle (Dark/Light Mode)
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('vehdoc-theme', theme);
        if (themeIcon) {
            themeIcon.className = theme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        }
    }

    // Load saved theme
    const savedTheme = localStorage.getItem('vehdoc-theme') || 'light';
    setTheme(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const current = document.documentElement.getAttribute('data-theme');
            setTheme(current === 'dark' ? 'light' : 'dark');
        });
    }

    // Back to Top
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // FAQ Accordion
    document.querySelectorAll('.faq-question').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const item = this.closest('.faq-item');
            const isActive = item.classList.contains('active');

            // Close all
            document.querySelectorAll('.faq-item').forEach(function(i) {
                i.classList.remove('active');
                i.querySelector('.faq-answer').style.maxHeight = null;
            });

            // Toggle current
            if (!isActive) {
                item.classList.add('active');
                var answer = item.querySelector('.faq-answer');
                answer.style.maxHeight = answer.scrollHeight + 'px';
            }
        });
    });

    // Animated Counter
    function animateCounters() {
        document.querySelectorAll('.stat-number[data-count]').forEach(function(el) {
            if (el.dataset.animated) return;

            var rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                el.dataset.animated = 'true';
                var target = parseInt(el.dataset.count);
                var duration = 2000;
                var start = 0;
                var startTime = null;

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    var eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * target).toLocaleString();
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        el.textContent = target.toLocaleString();
                    }
                }
                requestAnimationFrame(step);
            }
        });
    }

    window.addEventListener('scroll', animateCounters);
    animateCounters();

    // Scroll Reveal Animation
    function revealOnScroll() {
        document.querySelectorAll('.animate-fade-up, .animate-fade-in').forEach(function(el) {
            var rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight - 50) {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }
        });
    }
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();

    // Auth Forms
    // Login Form
    var loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = document.getElementById('loginBtn');
            showBtnLoader(btn, true);

            $.ajax({
                url: vehdocMain.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'vehdoc_login',
                    nonce: vehdocMain.nonce,
                    email: document.getElementById('login_email').value,
                    password: document.getElementById('login_password').value,
                },
                success: function(res) {
                    showBtnLoader(btn, false);
                    if (res.success) {
                        showAlert('loginAlert', res.data.message, 'success');
                        setTimeout(function() { window.location.href = res.data.redirect; }, 500);
                    } else {
                        showAlert('loginAlert', res.data.message, 'error');
                    }
                },
                error: function() {
                    showBtnLoader(btn, false);
                    showAlert('loginAlert', 'An error occurred. Please try again.', 'error');
                }
            });
        });
    }

    // Register Form
    var registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var password = document.getElementById('reg_password').value;
            var confirm = document.getElementById('reg_confirm').value;

            if (password !== confirm) {
                showAlert('registerAlert', 'Passwords do not match.', 'error');
                return;
            }

            var btn = document.getElementById('registerBtn');
            showBtnLoader(btn, true);

            $.ajax({
                url: vehdocMain.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'vehdoc_register',
                    nonce: vehdocMain.nonce,
                    full_name: document.getElementById('reg_name').value,
                    email: document.getElementById('reg_email').value,
                    phone: document.getElementById('reg_phone').value,
                    password: password,
                },
                success: function(res) {
                    showBtnLoader(btn, false);
                    if (res.success) {
                        showAlert('registerAlert', res.data.message, 'success');
                        setTimeout(function() { window.location.href = res.data.redirect; }, 500);
                    } else {
                        showAlert('registerAlert', res.data.message, 'error');
                    }
                },
                error: function() {
                    showBtnLoader(btn, false);
                    showAlert('registerAlert', 'An error occurred. Please try again.', 'error');
                }
            });
        });
    }

    // Forgot Password
    var forgotLink = document.getElementById('forgotPasswordLink');
    var forgotForm = document.getElementById('forgotPasswordForm');
    var backToLogin = document.getElementById('backToLogin');

    if (forgotLink && forgotForm && loginForm) {
        forgotLink.addEventListener('click', function(e) {
            e.preventDefault();
            loginForm.style.display = 'none';
            forgotForm.style.display = 'block';
        });
    }

    if (backToLogin && forgotForm && loginForm) {
        backToLogin.addEventListener('click', function() {
            forgotForm.style.display = 'none';
            loginForm.style.display = 'block';
        });
    }

    if (forgotForm) {
        forgotForm.addEventListener('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: vehdocMain.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'vehdoc_forgot_password',
                    nonce: vehdocMain.nonce,
                    email: document.getElementById('forgot_email').value,
                },
                success: function(res) {
                    showAlert('forgotAlert', res.success ? res.data.message : res.data.message, res.success ? 'success' : 'error');
                }
            });
        });
    }

    // Password Strength
    var passwordInput = document.getElementById('reg_password');
    var strengthEl = document.getElementById('passwordStrength');
    if (passwordInput && strengthEl) {
        passwordInput.addEventListener('input', function() {
            var val = this.value;
            var strength = 0;
            if (val.length >= 8) strength++;
            if (/[a-z]/.test(val) && /[A-Z]/.test(val)) strength++;
            if (/\d/.test(val)) strength++;
            if (/[^a-zA-Z\d]/.test(val)) strength++;

            var labels = ['Weak', 'Fair', 'Good', 'Strong'];
            var colors = ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'];
            var width = (strength / 4) * 100;

            strengthEl.innerHTML = '<div style="height:4px;background:#e2e8f0;border-radius:2px;margin-top:6px"><div style="height:100%;width:' + width + '%;background:' + (colors[strength - 1] || '#e2e8f0') + ';border-radius:2px;transition:all 0.3s"></div></div><span style="font-size:11px;color:' + (colors[strength - 1] || '#94a3b8') + '">' + (labels[strength - 1] || '') + '</span>';
        });
    }

    // Contact Form
    var contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = document.getElementById('contactBtn');
            showBtnLoader(btn, true);

            $.ajax({
                url: vehdocMain.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'vehdoc_contact',
                    nonce: vehdocMain.nonce,
                    name: document.getElementById('contact_name').value,
                    email: document.getElementById('contact_email').value,
                    phone: document.getElementById('contact_phone').value,
                    subject: document.getElementById('contact_subject').value,
                    message: document.getElementById('contact_message').value,
                },
                success: function(res) {
                    showBtnLoader(btn, false);
                    if (res.success) {
                        showAlert('contactAlert', res.data.message, 'success');
                        contactForm.reset();
                    } else {
                        showAlert('contactAlert', res.data.message, 'error');
                    }
                },
                error: function() {
                    showBtnLoader(btn, false);
                    showAlert('contactAlert', 'An error occurred. Please try again.', 'error');
                }
            });
        });
    }

    // Newsletter Form
    var newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var input = this.querySelector('input');
            if (input.value) {
                input.value = '';
                var btn = this.querySelector('button');
                btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(function() { btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i>'; }, 2000);
            }
        });
    }

    // Helpers
    window.togglePassword = function(id) {
        var input = document.getElementById(id);
        if (input) {
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    };

    function showAlert(id, message, type) {
        var el = document.getElementById(id);
        if (el) {
            el.className = 'form-alert ' + type;
            el.textContent = message;
            el.style.display = 'block';
        }
    }

    function showBtnLoader(btn, loading) {
        if (!btn) return;
        var text = btn.querySelector('.btn-text');
        var loader = btn.querySelector('.btn-loader');
        if (loading) {
            if (text) text.style.display = 'none';
            if (loader) loader.style.display = 'inline-flex';
            btn.disabled = true;
        } else {
            if (text) text.style.display = 'inline';
            if (loader) loader.style.display = 'none';
            btn.disabled = false;
        }
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                // Close mobile menu
                if (hamburger) hamburger.classList.remove('active');
                if (navMenu) navMenu.classList.remove('active');
                if (mobileOverlay) mobileOverlay.classList.remove('active');
            }
        });
    });

})(jQuery);
