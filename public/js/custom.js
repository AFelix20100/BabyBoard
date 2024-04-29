$(document).ready(function() {
    $('.js-example-basic-single').select2({
        theme: "classic",
        dropdownCss: {
            width: '100%', // Ajuster la largeur du menu déroulant
        }
    });
});
$(document).ready(function() {
    $('#myTable').DataTable({
        "language": {
            "sProcessing": "Traitement en cours...",
            "sLengthMenu": "Afficher _MENU_ éléments",
            "sZeroRecords": "Aucun résultat trouvé",
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
            "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
            "sInfoPostFix": "",
            "sSearch": "Rechercher :",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Chargement en cours...",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Aucune ligne sélectionnée",
                    "1": "1 ligne sélectionnée"
                }
            }
        }
    });
});
