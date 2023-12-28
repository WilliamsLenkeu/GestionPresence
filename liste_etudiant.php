<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de Bord - Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css" type="text/css" />
    <link rel="icon" href="./image/logo.jpg" type="image/x-icon">
</head>

<body class="fw-bold">
    <div class="container-fluid dash">

        <!-- Section du tableau de bord -->
        <div class="row dash-1">
            <div class="col-md-2 border-end border-secondary text-center">
                <?php
                include './navbar.php';
                ?>
            </div>
            <div class="col-md col-12">
                <div class="row page-title shadow-lg">
                    <div class="fs-2 mt-3"> Etudiants </div>
                </div>
                <div class="row mt-4 fw-normal">
                    <div class="mb-3">
                        <!-- Barre de recherche -->
                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher un étudiant">
                    </div>

                    <!-- Options de tri -->
                    <div class="mb-3">
                        <label for="sortOptions" class="form-label">Trier par :</label>
                        <select class="form-select" id="sortOptions">
                            <option value="classe">Classe</option>
                            <option value="age">Âge</option>
                        </select>
                    </div>

                    <!-- Tableau responsive -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Matricule</th>
                                    <th>Nom</th>
                                    <th>Classe</th>
                                    <th>Date de Naissance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Exemple de liste d'étudiants (à remplacer par votre propre logique de récupération des étudiants depuis la base de données)
                                $etudiants = array(
                                    array("001", "John Doe", "Classe A", "1995-05-15"),
                                    array("002", "Jane Doe", "Classe B", "1998-02-20"),
                                    array("003", "Alice Smith", "Classe A", "1997-09-10"),
                                    array("004", "Bob Johnson", "Classe A", "1996-12-25"),
                                    array("005", "Charlie Brown", "Classe A", "1999-07-18"),
                                    array("006", "Eva Green", "Classe A", "1994-03-05"),
                                    array("007", "Mike White", "Classe B", "1996-08-30"),
                                    array("008", "Sophie Miller", "Classe B", "1998-04-12"),
                                    array("009", "Daniel Davis", "Classe B", "1997-11-22"),
                                    array("010", "Laura Wilson", "Classe C", "1995-06-08"),
                                    array("011", "Tom Parker", "Classe C", "1999-01-14"),
                                );
                                

                                // Fonction pour trier les étudiants par classe
                                function sortByClasse($a, $b)
                                {
                                    return strcmp($a[2], $b[2]);
                                }

                                // Fonction pour trier les étudiants par âge
                                function sortByAge($a, $b)
                                {
                                    $dateA = new DateTime($a[3]);
                                    $dateB = new DateTime($b[3]);
                                    return $dateA <=> $dateB;
                                }

                                // Tri par défaut (par classe)
                                usort($etudiants, 'sortByClasse');

                                // Parcours de la liste des étudiants
                                foreach ($etudiants as $index => $etudiant) {
                                    echo '<tr>';
                                    echo '<td>' . ($index + 1) . '</td>';
                                    echo '<td>' . $etudiant[0] . '</td>';
                                    echo '<td>' . $etudiant[1] . '</td>';
                                    echo '<td>' . $etudiant[2] . '</td>';
                                    echo '<td>' . $etudiant[3] . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Colonne de droite pour les informations de l'utilisateur -->
            <div class="col-md-2 border-start border-secondary">
                <div class="card my-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informations Utilisateur</h5>
                    </div>
                    <div class="card-body">
                        <!-- Afficher les détails de l'utilisateur ici -->
                        <!-- <h6 class="card-subtitle mb-2 text-muted">Nom Utilisateur</h6> -->
                        <p class="card-text">Nom D'Utilisateur</p>
                        <p class="card-text">Etablissement: abcdef</p>
                        <p class="card-text">Email: utilisateur@example.com</p>
                        <!-- Ajoutez d'autres informations de l'utilisateur ici -->
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <!-- Bouton pour accéder au profil -->
                        <a href="#" class="btn btn-light btn-sm">Voir Profil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Script pour la recherche et le tri -->
    <script>
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

            // Fonction pour trier les résultats en fonction de l'option de tri sélectionnée
            function sortResults() {
                var sortBy = sortOptions.value;
                var rows = Array.from(tableBody.rows);

                // Fonction de comparaison en fonction de l'option de tri
                function compareRows(a, b) {
                    var cellA = a.cells.namedItem(sortBy).textContent;
                    var cellB = b.cells.namedItem(sortBy).textContent;

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
    </script>
</body>

</html>
