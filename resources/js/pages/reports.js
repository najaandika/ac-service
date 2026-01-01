
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const revenueCanvas = document.getElementById('revenueChart');
    const serviceCanvas = document.getElementById('serviceChart');

    if (revenueCanvas) {
        const chartData = JSON.parse(revenueCanvas.dataset.chart);

        new Chart(revenueCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: chartData.data,
                    backgroundColor: 'rgba(52, 152, 219, 0.8)',
                    borderColor: '#3498DB',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + (value / 1000) + 'K';
                            }
                        }
                    }
                }
            }
        });
    }

    if (serviceCanvas) {
        const chartData = JSON.parse(serviceCanvas.dataset.chart);

        new Chart(serviceCanvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.data,
                    backgroundColor: [
                        '#3498DB',
                        '#27AE60',
                        '#F1C40F',
                        '#E74C3C',
                        '#9B59B6',
                        '#1ABC9C'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 15,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
});
