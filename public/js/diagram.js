$(document).ready(function(){
    $.ajax({
        url: "/dashboard/mon-espace/victories-defeats",
        method: "POST",
        dataType: "json",
    })
        .done(function(response){
            const ctx = document.getElementById('myChart');
            const data = {
                labels: [
                    'Défaites',
                    'Victoires',
                ],
                datasets: [{
                    data: [response.nombre_defaites, response.nombre_victoires], // Utilisez les données reçues dans la réponse AJAX
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
        })
        .fail(function(error){
            console.log("Impossible de récupérer les statistiques");
        });
});
