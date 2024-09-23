console.log('chartModule.js is loaded');

document.addEventListener('DOMContentLoaded', (event) => {
    if (typeof Chart !== 'undefined') {
        console.log('Chart.js is loaded');
    } else {
        console.error('Chart.js is not loaded');
    }

    const ctx = document.getElementById('chartModule');

    const filteredData = chartData.filter(value => value !== null);

    // valeurs minimales et maximales réelles
    const minValue = Math.min(...filteredData);
    const maxValue = Math.max(...filteredData);

    // marge proportionnelle
    let margin = (maxValue - minValue) * 0.1;

    // si toutes les valeurs sont identiques
    if (minValue === maxValue) {
        margin = maxValue * 0.1; 
    }

    // Définir les limites minimales et maximales
    const minY = minValue - margin;
    const maxY = maxValue + margin;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Mesure Module',
                data: chartData,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: false,
                    min: minY,
                    max: maxY, 
                }
            }
        }
    });
});
