<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de Bord - Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css" type="text/css" />
  </head>
  <body class="text-bg-dar">
    <div class="container-fluid dash">
        
        <!-- Section du tableau de bord -->
        <div class="row dash-1">
            <div class="col-2 border-end border-secondary text-center">
                <?php
                    include './navbar.php';
                ?>    
            </div>
            <div class="col mx-3">
                <div class="row">
                    <nav class="navbar navbar-expand-lg bg-body-tertiary">
                        <div class="container-fluid">
                        <a class="navbar-brand" href="#">Navbar</a>
                        </div>
                    </nav>
                </div>
                <div class="row mt-4">
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
            <div class="col-2 bg-danger">
                
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
