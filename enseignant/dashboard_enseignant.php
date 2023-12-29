<?php
// Démarrez la session pour accéder aux variables de session
session_start();

// Vérifiez si le matricule est présent dans la session
if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page index.php du dossier parent
    header('Location: ../index.php');
    exit;
}

?>


<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de Bord - Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css" type="text/css" />
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
                    <div class="fs-2 mt-3"> Tableau de Bord </div>
                </div>
                <div class="row mt-4 fw-normal">
                    <!-- Carte des étudiants avec le plus d'absences -->
                    <div class="col-md-6 my-2">
                        <div class="card">
                            <div class="card-header">
                                Étudiants avec le plus d'absences
                            </div>
                            <div class="card-body">
                                <!-- Afficher la liste des étudiants avec le plus d'absences ici -->
                            </div>
                        </div>
                    </div>

                    <!-- Carte des notifications -->
                    <div class="col-md-6 my-2">
                        <div class="card">
                            <div class="card-header">
                                Notifications
                            </div>
                            <div class="card-body">
                                <!-- Afficher la liste des notifications ici -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 my-2">
                        <div class="card">
                            <div class="card-header">
                                Notifications
                            </div>
                            <div class="card-body">
                                <!-- Afficher la liste des notifications ici -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 my-2">
                        <div class="card">
                            <div class="card-header">
                                Notifications
                            </div>
                            <div class="card-body">
                                <!-- Afficher la liste des notifications ici -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 my-2">
                        <div class="card">
                            <div class="card-header">
                                Notifications
                            </div>
                            <div class="card-body">
                                <!-- Afficher la liste des notifications ici -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 my-2">
                        <div class="card">
                            <div class="card-header">
                                Notifications
                            </div>
                            <div class="card-body">
                                <!-- Afficher la liste des notifications ici -->
                            </div>
                        </div>
                    </div>



                    <!-- Ajoutez d'autres cartes pour d'autres fonctionnalités -->

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
</body>

</html>