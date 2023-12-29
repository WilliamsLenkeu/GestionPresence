<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste Des Etudiants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css" type="text/css" />
    <link rel="icon" href="../image/logo.jpg" type="image/x-icon">
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
                    <div class="mb-3 form-inline">
                        <label for="sortOptions" class="form-label mr-2">Trier par :</label>
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
                                    array("012", "Emma Lee", "Classe A", "1998-09-03"),
                                    array("013", "William Brown", "Classe B", "1997-04-17"),
                                    array("014", "Olivia Smith", "Classe A", "1996-10-28"),
                                    array("015", "Liam Davis", "Classe C", "1998-07-12"),
                                    array("016", "Ava Johnson", "Classe A", "1999-02-22"),
                                    array("017", "Mia Wilson", "Classe B", "1995-11-14"),
                                    array("018", "Noah Green", "Classe C", "1994-04-30"),
                                    array("019", "Sophia White", "Classe A", "1996-12-05"),
                                    array("020", "Ethan Miller", "Classe C", "1997-08-19")
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
                <?php
                    include '../templates/user-card.php';
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Script pour la recherche et le tri -->
    <script src="../js/recherche-tri.js"></script>
</body>

</html>
