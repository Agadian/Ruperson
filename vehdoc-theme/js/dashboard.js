/**
 * Vehdoc Dashboard JavaScript
 *
 * @package Vehdoc
 */

(function($) {
    'use strict';

    var ajaxUrl = vehdocDashboard.ajaxUrl;
    var nonce = vehdocDashboard.nonce;

    // Sidebar Toggle (Mobile)
    var sidebarToggle = document.getElementById('sidebarToggle');
    var dashSidebar = document.getElementById('dashSidebar');

    if (sidebarToggle && dashSidebar) {
        sidebarToggle.addEventListener('click', function() {
            dashSidebar.classList.toggle('active');
        });
    }

    // Add Vehicle Form
    var addVehicleForm = document.getElementById('addVehicleForm');
    if (addVehicleForm) {
        addVehicleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('action', 'vehdoc_add_vehicle');
            formData.append('nonce', nonce);

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        showFormAlert('vehicleAlert', res.data.message, 'success');
                        setTimeout(function() { location.reload(); }, 1000);
                    } else {
                        showFormAlert('vehicleAlert', res.data.message, 'error');
                    }
                },
                error: function() {
                    showFormAlert('vehicleAlert', 'An error occurred.', 'error');
                }
            });
        });
    }

    // New Order Form
    var newOrderForm = document.getElementById('newOrderForm');
    if (newOrderForm) {
        // Update summary when selections change
        var serviceRadios = newOrderForm.querySelectorAll('input[name="service_id"]');
        var fastTrackCheckbox = newOrderForm.querySelector('input[name="fast_track"]');
        var deliveryCheckbox = newOrderForm.querySelector('input[name="delivery"]');
        var expressCheckbox = newOrderForm.querySelector('input[name="express_delivery"]');

        function updateOrderSummary() {
            var selectedService = newOrderForm.querySelector('input[name="service_id"]:checked');
            if (!selectedService) return;

            var card = selectedService.closest('.service-select-card');
            var serviceName = card.querySelector('h4').textContent;
            var priceText = card.querySelector('.ssc-price').textContent;
            var basePrice = parseInt(priceText.replace(/[^0-9]/g, ''));

            // Get fast track price (1.5x as set during activation)
            var price = basePrice;
            if (fastTrackCheckbox && fastTrackCheckbox.checked) {
                price = Math.round(basePrice * 1.5);
            }

            var deliveryFee = 0;
            if (deliveryCheckbox && deliveryCheckbox.checked) {
                deliveryFee = 3000;
                if (expressCheckbox && expressCheckbox.checked) {
                    deliveryFee = 5000;
                }
            }

            var total = price + deliveryFee;

            document.getElementById('summaryService').textContent = serviceName;
            document.getElementById('summaryPrice').textContent = '₦' + price.toLocaleString();
            document.getElementById('summaryDelivery').textContent = '₦' + deliveryFee.toLocaleString();
            document.getElementById('summaryTotal').textContent = '₦' + total.toLocaleString();

            // Show/hide delivery details
            var deliveryDetails = document.getElementById('deliveryDetails');
            if (deliveryDetails) {
                deliveryDetails.style.display = deliveryCheckbox && deliveryCheckbox.checked ? 'block' : 'none';
            }
        }

        serviceRadios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                // Remove selected class from all
                document.querySelectorAll('.service-select-card').forEach(function(c) { c.classList.remove('selected'); });
                this.closest('.service-select-card').classList.add('selected');
                updateOrderSummary();
            });
        });

        if (fastTrackCheckbox) fastTrackCheckbox.addEventListener('change', updateOrderSummary);
        if (deliveryCheckbox) deliveryCheckbox.addEventListener('change', updateOrderSummary);
        if (expressCheckbox) expressCheckbox.addEventListener('change', updateOrderSummary);

        // Vehicle selection
        newOrderForm.querySelectorAll('input[name="vehicle_id"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.vehicle-select-card').forEach(function(c) { c.classList.remove('selected'); });
                this.closest('.vehicle-select-card').classList.add('selected');
            });
        });

        // Initialize summary
        updateOrderSummary();

        // Submit order
        newOrderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = document.getElementById('createOrderBtn');
            showBtnLoader(btn, true);

            var formData = new FormData(this);
            formData.append('action', 'vehdoc_create_order');
            formData.append('nonce', nonce);

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    showBtnLoader(btn, false);
                    if (res.success) {
                        showFormAlert('orderAlert', res.data.message, 'success');
                        // Redirect to checkout
                        setTimeout(function() {
                            window.location.href = vehdocDashboard.restUrl.replace('/wp-json/vehdoc/v1/', '') + '/checkout/?order_id=' + res.data.order_id;
                        }, 1000);
                    } else {
                        showFormAlert('orderAlert', res.data.message, 'error');
                    }
                },
                error: function() {
                    showBtnLoader(btn, false);
                    showFormAlert('orderAlert', 'An error occurred.', 'error');
                }
            });
        });
    }

    // Document Upload
    var uploadForm = document.getElementById('uploadForm');
    var uploadArea = document.getElementById('uploadArea');
    var docFileInput = document.getElementById('docFileInput');
    var uploadPreview = document.getElementById('uploadPreview');
    var uploadBtn = document.getElementById('uploadBtn');

    if (uploadArea && docFileInput) {
        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        uploadArea.addEventListener('dragleave', function() {
            this.classList.remove('dragover');
        });
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            docFileInput.files = e.dataTransfer.files;
            showFilePreview(e.dataTransfer.files);
        });

        docFileInput.addEventListener('change', function() {
            showFilePreview(this.files);
        });
    }

    function showFilePreview(files) {
        if (!uploadPreview || !uploadBtn) return;
        uploadPreview.innerHTML = '';
        if (files.length > 0) {
            uploadBtn.style.display = 'block';
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var icon = file.type.includes('pdf') ? 'fa-file-pdf' : 'fa-file-image';
                var size = (file.size / 1024 / 1024).toFixed(2);
                uploadPreview.innerHTML += '<div style="display:flex;align-items:center;gap:8px;padding:12px;background:var(--bg-secondary);border-radius:8px;border:1px solid var(--border)"><i class="fa-solid ' + icon + '" style="font-size:1.5rem;color:var(--accent)"></i><div><strong style="font-size:0.813rem">' + file.name + '</strong><br><span style="font-size:0.688rem;color:var(--text-muted)">' + size + ' MB</span></div></div>';
            }
        } else {
            uploadBtn.style.display = 'none';
        }
    }

    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = uploadBtn;
            showBtnLoader(btn, true);

            var formData = new FormData(this);
            formData.append('action', 'vehdoc_upload_documents');
            formData.append('nonce', nonce);

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    showBtnLoader(btn, false);
                    if (res.success) {
                        alert(res.data.message);
                        location.reload();
                    } else {
                        alert(res.data.message || 'Upload failed.');
                    }
                },
                error: function() {
                    showBtnLoader(btn, false);
                    alert('Upload error. Please try again.');
                }
            });
        });
    }

    // Profile Form
    var profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = {};
            var inputs = this.querySelectorAll('input, select, textarea');
            inputs.forEach(function(input) {
                if (input.name && !input.disabled) {
                    formData[input.name] = input.value;
                }
            });

            $.ajax({
                url: vehdocDashboard.restUrl + 'profile',
                type: 'PUT',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                headers: { 'X-WP-Nonce': nonce },
                success: function(res) {
                    showFormAlert('profileAlert', 'Profile updated successfully!', 'success');
                },
                error: function() {
                    showFormAlert('profileAlert', 'Failed to update profile.', 'error');
                }
            });
        });
    }

    // Helpers
    function showFormAlert(id, message, type) {
        var el = document.getElementById(id);
        if (el) {
            el.className = 'form-alert ' + type;
            el.textContent = message;
            el.style.display = 'block';
            el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
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

})(jQuery);
