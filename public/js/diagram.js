const ctx = document.getElementById('myChart');

const data = {
    labels: [
        'Défaites',
        'Victoires',
    ],
    datasets: [{
        data: [25, 75], // Remplacez ces valeurs par vos propres données
        backgroundColor: [
            'rgb(210, 34, 45)', // Couleur pour les défaites
            'rgb(35, 136, 35)', // Couleur pour les victoires
        ]
    }]
};

const options = {
    responsive: true, // Rendre le graphique réactif
    maintainAspectRatio: false, // Empêcher le graphique de conserver un aspect ratio fixe
    title: {
        display: true,
        text: 'Répartition des victoires et des défaites'
    }
};

new Chart(ctx, {
    type: 'pie',
    data: data,
    options: options
});
