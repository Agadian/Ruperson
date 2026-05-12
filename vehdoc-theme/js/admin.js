/**
 * Vehdoc Admin JavaScript
 *
 * @package Vehdoc
 */

(function($) {
    'use strict';

    // Revenue Chart
    if (typeof vehdocAdmin !== 'undefined' && vehdocAdmin.monthlyRevenue && typeof Chart !== 'undefined') {
        var ctx = document.getElementById('revenueChart');
        if (ctx) {
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var data = [];
            for (var i = 1; i <= 12; i++) {
                data.push(vehdocAdmin.monthlyRevenue[i] || 0);
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Revenue (₦)',
                        data: data,
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 6,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '₦' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₦' + value.toLocaleString();
                                }
                            },
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    }

    // Export Customers
    var exportBtn = document.getElementById('exportCustomersBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = ajaxurl + '?action=vehdoc_export_customers&nonce=' + vehdocAdmin.nonce;
        });
    }

})(jQuery);
