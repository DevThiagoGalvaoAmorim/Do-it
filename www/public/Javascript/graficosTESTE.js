const chartData = {
    vendas: {
        labels: ['Smartphones', 'Laptops', 'Tablets', 'Acessórios', 'Outros'],
        data: [35, 25, 15, 15, 10],
        colors: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
    },
    navegadores: {
        labels: ['Chrome', 'Firefox', 'Safari', 'Edge', 'Outros'],
        data: [65, 15, 10, 7, 3],
        colors: ['#4285F4', '#FF7139', '#1ABCFE', '#00BCF2', '#FF6B6B']
    },
    idades: {
        labels: ['18-25', '26-35', '36-45', '46-55', '56+'],
        data: [22, 28, 25, 15, 10],
        colors: ['#E74C3C', '#F39C12', '#F1C40F', '#27AE60', '#8E44AD']
    }
};

// Configuração do gráfico
const ctx = document.getElementById('pieChart').getContext('2d');
let myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: chartData.vendas.labels,
        datasets: [{
            data: chartData.vendas.data,
            backgroundColor: chartData.vendas.colors,
            borderColor: '#ffffff',
            borderWidth: 2,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Vendas por Produto (%)',
                font: {
                    size: 18,
                    weight: 'bold'
                },
                padding: 20
            },
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value}% (${percentage}% do total)`;
                    }
                }
            }
        },
        animation: {
            animateRotate: true,
            animateScale: true,
            duration: 1000
        }
    }
});

// Adicionar interatividade ao clicar nas fatias
ctx.onclick = function(event) {
    const points = myPieChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
    if (points.length) {
        const firstPoint = points[0];
        const label = myPieChart.data.labels[firstPoint.index];
        const value = myPieChart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
        alert(`Você clicou em: ${label}\nValor: ${value}%`);
    }
};