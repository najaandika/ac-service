
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const revenueCanvas = document.getElementById('revenueChart');
    const statusCanvas = document.getElementById('statusChart');

    if (revenueCanvas) {
        const chartData = JSON.parse(revenueCanvas.dataset.chart);

        new Chart(revenueCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: chartData.data,
                    borderColor: '#3498DB',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3498DB',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    if (statusCanvas) {
        const chartData = JSON.parse(statusCanvas.dataset.chart);

        new Chart(statusCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Dikonfirmasi', 'Dalam Proses', 'Selesai', 'Dibatalkan'],
                datasets: [{
                    data: chartData,
                    backgroundColor: [
                        '#F1C40F',
                        '#3498DB',
                        '#9B59B6',
                        '#27AE60',
                        '#E74C3C'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }
});
