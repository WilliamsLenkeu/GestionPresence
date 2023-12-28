<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de Bord - Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css" type="text/css" />
    <link rel="icon" href="./image/logo.jpg" type="image/x-icon">
    <link rel="shortcut icon" href="./image/logo.jpg" type="image/x-icon">
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
                <div class="row page-title shadow-sm">
                    <div class="fs-2 mt-3"> Listes D'appels </div>
                </div>
                <div class="row mt-4 fw-normal">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Matricule</th>
                                <th scope="col">Classe</th>
                                <th scope="col">Présent</th>
                                <th scope="col">Absent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Assumez que $etudiants est un tableau d'étudiants avec leurs informations
                            $etudiants = array(
                                array("Nom1", "Matricule1", "Classe1"),
                                array("Nom2", "Matricule2", "Classe2"),
                                // Ajoutez d'autres étudiants ici
                            );

                            foreach ($etudiants as $index => $etudiant) {
                                echo '<tr>';
                                echo '<th scope="row">' . ($index + 1) . '</th>';
                                echo '<td>' . $etudiant[0] . '</td>';
                                echo '<td>' . $etudiant[1] . '</td>';
                                echo '<td>' . $etudiant[2] . '</td>';
                                echo '<td><input type="checkbox" name="present_' . $index . '" value="present"></td>';
                                echo '<td><input type="checkbox" name="absent_' . $index . '" value="absent"></td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
</body>

</html>