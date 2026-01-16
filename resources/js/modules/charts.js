/**
 * Dashboard Charts
 * Chart.js configurations for the dashboard
 */

// Theme colors
const colors = {
    primary: '#0891B2',
    primaryLight: 'rgba(8, 145, 178, 0.1)',
    accentTeal: '#82D9D7',
    accentTealLight: 'rgba(130, 217, 215, 0.1)',
    accentLime: '#C5E151',
    accentPeach: '#FAAC7B',
    success: '#22C55E',
    warning: '#EAB308',
};

// Common chart options
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    animation: {
        duration: 800,
        easing: 'easeOutQuart'
    },
    plugins: {
        legend: { position: 'bottom' }
    }
};

/**
 * Initialize all dashboard charts
 */
export function initDashboardCharts() {
    initRevenueTrendChart();
    initTopServicesChart();
    initTechnicianPerformanceChart();
}

/**
 * Revenue Trend Line Chart
 */
function initRevenueTrendChart() {
    const ctx = document.getElementById('revenueTrendChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr'],
            datasets: [
                {
                    label: 'Tahun Ini',
                    data: [28, 65, 42, 78],
                    borderColor: colors.primary,
                    backgroundColor: colors.primaryLight,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Tahun Lalu',
                    data: [35, 48, 58, 52],
                    borderColor: colors.accentTeal,
                    backgroundColor: colors.accentTealLight,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => `Rp ${value}Jt`
                    }
                }
            }
        }
    });
}

/**
 * Top Services Doughnut Chart
 */
function initTopServicesChart() {
    const ctx = document.getElementById('topServicesChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Cuci AC', 'Isi Freon', 'Perbaikan', 'Instalasi'],
            datasets: [{
                data: [35, 25, 20, 20],
                backgroundColor: [colors.success, colors.accentLime, colors.accentTeal, colors.warning]
            }]
        },
        options: commonOptions
    });
}

/**
 * Technician Performance Bar Chart
 */
function initTechnicianPerformanceChart() {
    const ctx = document.getElementById('technicianPerformanceChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Dedi', 'Agus', 'Rudi', 'Hendra'],
            datasets: [{
                label: 'Pendapatan (Jt)',
                data: [12.5, 10.8, 9.2, 7.5],
                backgroundColor: [colors.accentLime, colors.accentTeal, colors.accentPeach, colors.accentLime]
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => `Rp ${value}Jt`
                    }
                }
            }
        }
    });
}
