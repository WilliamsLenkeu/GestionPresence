<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de Bord - Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css" type="text/css" />
    <link rel="stylesheet" href="./css/style.css" type="text/css" />
    <link rel="icon" href="./image/logo.jpg" type="image/x-icon">
</head>

<body class="fw-bold">
    <div class="container-fluid dash">

        <!-- Section du tableau de bord -->
        <div class="row dash-1">
            <div class="col-md-2 border-end border-secondary text-center">
                <?php
                include './templates/navbar.php';
                ?>
            </div>
            <div class="col-md col-12">
                <div class="row page-title shadow-lg">
                    <div class="fs-2 mt-3"> Liste Des Cours </div>
                </div>
                <div class="row mt-4 fw-normal">
                    <!-- Tableau responsive -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code du Cours</th>
                                    <th>Nom du Cours</th>
                                    <th>Enseignant</th>
                                    <th>Nombre d'Élèves</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Exemple de liste de cours (à remplacer par votre propre logique de récupération des cours depuis la base de données)
                                $cours = array(
                                    array("ICT201", "Informatique Avancée", "Professeur A", 30),
                                    array("ICT202", "Base de Données", "Professeur B", 25),
                                    array("ICT203", "Réseaux Informatiques", "Professeur C", 20),
                                    // Ajoutez d'autres cours ici
                                );

                                // Parcours de la liste des cours
                                foreach ($cours as $index => $coursInfo) {
                                    echo '<tr>';
                                    echo '<td>' . ($index + 1) . '</td>';
                                    echo '<td>' . $coursInfo[0] . '</td>';
                                    echo '<td>' . $coursInfo[1] . '</td>';
                                    echo '<td>' . $coursInfo[2] . '</td>';
                                    echo '<td>' . $coursInfo[3] . '</td>';
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
                    include './templates/user-card.php';
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>