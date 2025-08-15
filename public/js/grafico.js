document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('graficaAvanceCorde');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    // Obtener datos desde atributos data
    const labels = JSON.parse(canvas.dataset.labels || '[]');
    const data = JSON.parse(canvas.dataset.data || '[]');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: '% Avance',
                data: data,
                backgroundColor: 'rgba(154, 42, 42, 0.7)',
                borderColor: 'rgba(100, 30, 22, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'CCT del plantel',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: (v) => v + '%'
                    },
                    title: {
                        display: true,
                        text: 'Porcentaje de Avance'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: (c) => (c.dataset.label || '') + ': ' + c.parsed.y.toFixed(2) + '%'
                    }
                }
            }
        }
    });
});
