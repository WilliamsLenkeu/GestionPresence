<?php
// Vérifier si l'utilisateur est connecté
session_start();

if (!isset($_SESSION['matricule'])) {
    // Rediriger vers la page logout.php du dossier parent
    header('Location: ../logout.php');
    exit;
}

// Inclure le fichier de connexion à la base de données
include '../connexion.php';

// Récupérer la classe de l'étudiant
$sqlClasse = "SELECT classe_id FROM utilisateur WHERE matricule = ?";
$stmtClasse = $conn->prepare($sqlClasse);
$stmtClasse->bind_param('s', $_SESSION['matricule']);
$stmtClasse->execute();
$resultClasse = $stmtClasse->get_result();
$classeId = $resultClasse->fetch_assoc()['classe_id'];
$stmtClasse->close();

// Vérifier si l'étudiant est déjà inscrit à un cours non facultatif
$sqlCheckInscription = "SELECT COUNT(*) AS count FROM attribution_cours 
                       JOIN cours ON attribution_cours.cours_id = cours.id
                       WHERE attribution_cours.utilisateur_matricule = ? AND cours.facultatif = 0";
$stmtCheckInscription = $conn->prepare($sqlCheckInscription);
$stmtCheckInscription->bind_param('s', $_SESSION['matricule']);
$stmtCheckInscription->execute();
$resultCheckInscription = $stmtCheckInscription->get_result();
$countInscription = $resultCheckInscription->fetch_assoc()['count'];
$stmtCheckInscription->close();

if ($countInscription == 0) {
    // L'étudiant n'est pas encore inscrit à un cours non facultatif, on l'inscrit
    $sqlInscription = "INSERT INTO attribution_cours (utilisateur_matricule, cours_id) 
                       SELECT ?, id FROM cours WHERE classe_id = ? AND facultatif = 0";
    $stmtInscription = $conn->prepare($sqlInscription);
    $stmtInscription->bind_param('si', $_SESSION['matricule'], $classeId);
    $stmtInscription->execute();
    $stmtInscription->close();
}

// Le reste du code HTML
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
        <div class="row">
            <div class="col-md col-12 text-center">
                <?php
                include './navbar.php';
                ?>
            </div>
            <!-- Colonne de droite pour les informations de l'utilisateur -->
            <div class="col-md-2 border-start border-secondary">
                <?php
                include './user-card.php';
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
