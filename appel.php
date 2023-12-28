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
                    include './templates/navbar.php';
                ?>
            </div>
            <div class="col-md col-12">
                <div class="row page-title shadow-lg">
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