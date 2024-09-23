console.log('generateData.js is loaded');

function generateRandomData(modules) {
    const data = modules.map(module => {
        let date = new Date();
        date.setMinutes(date.getMinutes()); 

        const etat = Math.random() > 0.05; 
        const valeur = etat ? (Math.random() * 200 - 100).toFixed(2) : null; 

        return {
            id_module: module.id_module,
            valeur: valeur,
            etat: etat,
            date: date.toLocaleString('sv-SE', { timeZone: 'UTC' }).replace('T', ' ') 
        };
    });
    return data;
}

function sendDataToServer(data) {
    fetch('/api/add-data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => console.log('Success:', data))
    .catch((error) => console.error('Error:', error));
}

function fetchModulesAndGenerateData() {
    fetch('/api/get-modules')
    .then(response => response.json())
    .then(modules => {
        const data = generateRandomData(modules);
        sendDataToServer(data);
    })
    .catch((error) => console.error('Error:', error));
}


setInterval(fetchModulesAndGenerateData, 30000); 
