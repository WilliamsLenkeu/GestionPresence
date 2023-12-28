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
                    <ul class="list-group">
                        <?php
                            $cours = array("Cours 1", "Cours 2", "Cours 3", "Cours 4");

                            foreach ($cours as $index => $nomCours) {
                                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                echo '<span>' . ($index + 1) . '. ' . $nomCours . '</span>';
                                echo '<a href="appel_etudiant.php?cours=' . urlencode($nomCours) . '" class="btn btn-primary btn-sm">Faire l\'appel</a>';
                                echo '</li>';
                            }
                        ?>
                    </ul>
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