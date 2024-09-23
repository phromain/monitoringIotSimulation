console.log('chartModule.js is loaded');

document.addEventListener('DOMContentLoaded', (event) => {
    if (typeof Chart !== 'undefined') {
        console.log('Chart.js is loaded');
    } else {
        console.error('Chart.js is not loaded');
    }

    document.querySelectorAll('canvas[id^="chart-"], canvas[id="chartModule"]').forEach(canvas => {
        const chartLabels = JSON.parse(canvas.getAttribute('data-chart-labels'));
        const chartData = JSON.parse(canvas.getAttribute('data-chart-data'));
        const ctx = canvas.getContext('2d');

        const filteredData = chartData.filter(value => value !== null);

        const minValue = Math.min(...filteredData);
        const maxValue = Math.max(...filteredData);

        let margin = (maxValue - minValue) * 0.1;

        if (minValue === maxValue) {
            margin = maxValue * 0.1; 
        }

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

    // Vérifie l'état des modules 
    function checkModuleStatus() {
        let modulesInError = [];
        document.querySelectorAll('canvas[id^="chart-"]').forEach(canvas => {
            const dernierReleveEtat = canvas.getAttribute('data-dernier-releve-etat');
            if (dernierReleveEtat === 'false') {
                modulesInError.push(canvas.getAttribute('data-module-nom'));
            }
        });

        if (modulesInError.length > 0) {
            toastr.options.timeOut = 10000; 
            toastr.error('Les modules suivants sont en erreur : ' + modulesInError.join(', '), 'Erreur de Module');
        }
    }

    checkModuleStatus();

    setInterval(function() {
        location.reload();
    }, 31000);
});
