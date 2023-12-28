document.addEventListener('DOMContentLoaded', function () {
    // Référence aux éléments du DOM
    var searchInput = document.getElementById('searchInput');
    var sortOptions = document.getElementById('sortOptions');
    var tableBody = document.querySelector('.table tbody');

    // Fonction pour filtrer les résultats en fonction de la recherche
    function filterResults() {
        var searchTerm = searchInput.value.toLowerCase();

        // Afficher ou masquer les lignes du tableau en fonction de la recherche
        Array.from(tableBody.rows).forEach(function (row) {
            var shouldShow = Array.from(row.cells).some(function (cell) {
                return cell.textContent.toLowerCase().includes(searchTerm);
            });
            row.style.display = shouldShow ? '' : 'none';
        });
    }

    // Fonction pour calculer l'âge à partir de la date de naissance
    function calculateAge(dateOfBirth) {
        var today = new Date();
        var birthDate = new Date(dateOfBirth);
        var age = today.getFullYear() - birthDate.getFullYear();
        var monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        return age;
    }

    // Fonction pour trier les résultats en fonction de l'option de tri sélectionnée
    function sortResults() {
        var sortBy = sortOptions.value;
        var rows = Array.from(tableBody.rows);

        // Fonction de comparaison en fonction de l'option de tri
        function compareRows(a, b) {
            var cellA, cellB;

            if (sortBy === 'age') {
                // Utiliser la fonction calculateAge pour trier par âge
                cellA = calculateAge(a.cells.namedItem('dateNaissance').textContent);
                cellB = calculateAge(b.cells.namedItem('dateNaissance').textContent);
            } else {
                cellA = a.cells.namedItem(sortBy).textContent;
                cellB = b.cells.namedItem(sortBy).textContent;
            }

            if (sortBy === 'dateNaissance') {
                // Conversion des dates pour le tri par âge
                var dateA = new Date(cellA);
                var dateB = new Date(cellB);
                return dateA - dateB;
            } else {
                // Tri par chaîne de caractères
                return cellA.localeCompare(cellB);
            }
        }

        // Trier et réinsérer les lignes dans le tableau
        rows.sort(compareRows);
        tableBody.innerHTML = '';
        rows.forEach(function (row) {
            tableBody.appendChild(row);
        });
    }

    // Écouteurs d'événements pour la recherche et le tri
    searchInput.addEventListener('input', filterResults);
    sortOptions.addEventListener('change', sortResults);
});
