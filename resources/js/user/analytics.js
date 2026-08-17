import Chart from 'chart.js/auto';

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var pageData = JSON.parse(document.getElementById('analyticsData').textContent);

        var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        var textColor = isDark ? '#a5b4c8' : '#4b5563';
        var gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
        var accentColor = '#6366f1';
        var greenColor = '#10b981';
        var yellowColor = '#f59e0b';
        var pinkColor = '#ec4899';

        function renderChart(canvasId, config) {
            var ctx = document.getElementById(canvasId);
            if (!ctx) return;
            return new Chart(ctx.getContext('2d'), config);
        }

        var trendData = pageData.dailyData;
        var trendLabels = Object.keys(trendData);
        var trendValues = Object.values(trendData);

        renderChart('trendChart', {
            type: 'line',
            data: {
                labels: trendLabels.map(function (d) { var p = d.split('-'); return p[2] + '/' + p[1]; }),
                datasets: [{
                    label: 'Donations',
                    data: trendValues,
                    borderColor: accentColor,
                    backgroundColor: 'rgba(99,102,241,0.10)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: accentColor,
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#1a1b2e' : '#fff',
                        titleColor: isDark ? '#f0f1ff' : '#0f1117',
                        bodyColor: isDark ? '#a5b4c8' : '#4b5563',
                        borderColor: isDark ? 'rgba(255,255,255,0.10)' : 'rgba(0,0,0,0.10)',
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function (ctx) { return '₹' + Number(ctx.raw).toLocaleString('en-IN'); }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: textColor, font: { size: 10, family: "'DM Mono',monospace" }, maxTicksLimit: 12 },
                        grid: { color: gridColor }
                    },
                    y: {
                        ticks: { color: textColor, font: { size: 10, family: "'DM Mono',monospace" }, callback: function (v) { return '₹' + v.toLocaleString('en-IN'); } },
                        grid: { color: gridColor }
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });

        var typeData = pageData.donationTypeBreakdown;
        renderChart('typeChart', {
            type: 'doughnut',
            data: {
                labels: typeData.map(function (d) { return d.type + ' (₹' + Number(d.amount).toLocaleString('en-IN') + ')'; }),
                datasets: [{
                    data: typeData.map(function (d) { return d.amount; }),
                    backgroundColor: [accentColor, greenColor],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: textColor, font: { size: 11, family: "'DM Sans',sans-serif" }, padding: 14 }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                var d = typeData[ctx.dataIndex];
                                return d.type + ': ₹' + Number(d.amount).toLocaleString('en-IN') + ' (' + d.count + ' donations)';
                            }
                        }
                    }
                }
            }
        });

        renderChart('dayChart', {
            type: 'bar',
            data: {
                labels: pageData.dayOfWeekLabels,
                datasets: [{
                    label: 'Amount',
                    data: pageData.dayOfWeekTotals,
                    backgroundColor: [
                        'rgba(99,102,241,0.6)', 'rgba(16,185,129,0.6)', 'rgba(245,158,11,0.6)',
                        'rgba(236,72,153,0.6)', 'rgba(59,130,246,0.6)', 'rgba(239,68,68,0.6)',
                        'rgba(99,102,241,0.6)'
                    ],
                    borderColor: [
                        '#6366f1', '#10b981', '#f59e0b', '#ec4899', '#3b82f6', '#ef4444', '#6366f1'
                    ],
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                var count = pageData.dayOfWeekCounts[ctx.dataIndex];
                                return '₹' + Number(ctx.raw).toLocaleString('en-IN') + ' (' + count + ' donations)';
                            }
                        }
                    }
                },
                scales: {
                    x: { ticks: { color: textColor, font: { size: 10 } }, grid: { color: gridColor } },
                    y: { ticks: { color: textColor, font: { size: 10, family: "'DM Mono',monospace" }, callback: function (v) { return '₹' + v.toLocaleString('en-IN'); } }, grid: { color: gridColor } }
                }
            }
        });
    });
})();