<?php
?>

<!DOCTYPE html>
<html lang="fr">

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Study Check 📚</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css" type="text/css" />
    <link rel="icon" href="./image/logo.jpg" type="image/x-icon">
    <style>
        body {
            background-image: url('./image/background.jpeg');
            background-repeat: no-repeat;
            background-size: cover;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .navbar {
            border-bottom: 0.5px solid ;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.8);
            border: none;
            backdrop-filter: blur(10px);
        }

        .card-header {
            background-color: rgb(68, 68, 68);
            color: #FFF;
            border-radius: 20px;
            border-bottom: none;
        }

        .logo-form {
            max-width: 200px;
            margin-bottom: 15px;
            /* border: 2px solid red; */
        }

        .btn-group button {
            background-color: #444;
            color: #FFF;
            border: none;
        }

        .btn-group .active {
            background-color: #FFF;
            color: #444;
        }

        .form-label,
        .form-control {
            color: #444;
        }

        .btn-primary {
            background-color: #444;
            border: none;
        }

        .btn-primary:hover {
            background-color: #666;
        }
    </style>
</head>

<body class="fw-bold">
    <div class="container-fluid dash">

        <!-- Barre de navigation -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <!-- Logo -->
                <a class="navbar-brand" href="#">
                    <img src="../image/logo-banner.jpg" alt="Logo" width="30%">
                    <span class="xxl" style="font-size: 200%;">study check</span>
                    <span class="lead" style="font-size: 80%;">(Gerez, suivez, excellez)</span>
                </a>
            </div>
        </nav>



        <!-- Section du tableau de bord -->
        <div class="row dash-1">
            <div class="col-md col-12 text-center">
                <?php
                include './navbar.php';
                ?>
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
