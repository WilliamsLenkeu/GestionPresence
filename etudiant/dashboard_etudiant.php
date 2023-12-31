<?php
// Démarrez la session pour accéder aux variables de session
session_start();

// Vérifiez si le matricule est présent dans la session
if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page logout.php du dossier parent
    header('Location: ../logout.php');
    exit;
}

// Vérifier si l'étudiant a un profil
include '../connexion.php';

$utilisateur_matricule = $_SESSION['matricule'];

// Récupérer la classe de l'étudiant
$sqlClasse = "SELECT classe_id FROM utilisateur WHERE matricule = ?";
$stmtClasse = $conn->prepare($sqlClasse);
$stmtClasse->bind_param("i", $utilisateur_matricule);
$stmtClasse->execute();
$stmtClasse->store_result();
$stmtClasse->bind_result($classe_id);
$stmtClasse->fetch();

// Récupérer les cours non facultatifs de la classe de l'étudiant
$sqlCours = "SELECT id FROM cours WHERE classe_id = ? AND facultatif = 0";
$stmtCours = $conn->prepare($sqlCours);
$stmtCours->bind_param("i", $classe_id);
$stmtCours->execute();
$resultCours = $stmtCours->get_result();

// Insérer l'étudiant dans les cours qui ne sont pas facultatifs et auxquels il n'est pas déjà inscrit
while ($cours = $resultCours->fetch_assoc()) {
    $cours_id = $cours['id'];

    // Vérifier si l'étudiant est déjà inscrit à ce cours
    $sqlCheckInscription = "SELECT id FROM attribution_cours WHERE utilisateur_matricule = ? AND cours_id = ?";
    $stmtCheckInscription = $conn->prepare($sqlCheckInscription);
    $stmtCheckInscription->bind_param("ii", $utilisateur_matricule, $cours_id);
    $stmtCheckInscription->execute();
    $resultCheckInscription = $stmtCheckInscription->get_result();

    // Si l'étudiant n'est pas déjà inscrit, l'insérer
    if ($resultCheckInscription->num_rows == 0) {
        $sqlInsertInscription = "INSERT INTO attribution_cours (utilisateur_matricule, cours_id, classe_id) VALUES (?, ?, ?)";
        $stmtInsertInscription = $conn->prepare($sqlInsertInscription);
        $stmtInsertInscription->bind_param("iii", $utilisateur_matricule, $cours_id, $classe_id);
        $stmtInsertInscription->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de Bord - Etudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css" type="text/css" />
    <link rel="stylesheet" href="../css/style.css" type="text/css" />
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
        }
        .navbar-brand {
            font-size: 20px;
            font-weight: bold;
        }

        .navbar-brand img {
            margin-right: 10px;
            height: 90px;
            width: 90px;
            object-fit: cover;
        }

        .navbar-light .navbar-nav .nav-link {
            color: #fff;
        }

        .navbar-light .navbar-toggler-icon {
            background-color: #fff;
        }

        .dash-1 {
            padding: 20px 0;
        }

        

        .shadow-none {
            box-shadow: none;
        }

        .border-secondary {
            border-color: #d3d3d3;
        }

        .user-card {
            background-color: #fff;
            border: 1px solid #d3d3d3;
            border-radius: 8px;
            padding: 15px;
        }
    </style>

    <link rel="icon" href="../image/logo.jpg" type="image/x-icon">
</head>

<body class="fw-bold">
    <div class="container-fluid dash">

        <!-- Barre de navigation -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <!-- Logo -->
                <a class="navbar-brand" href="#">
                    <img src="../image/logo-banner.jpg" alt="Logo"> Gerez, suivez, excellez. Study Check
                </a>
            </div>
        </nav>

        <!-- Section du tableau de bord -->
        <div class="row dash-1">
            <div class="col-md-4 text-center">
                <?php
                include './navbar.php';
                ?>
            </div>
            <div class="col-md col-12 ">
                <div class="row shadow-none card-header">
                    <div class="fs-2 mt-3 card-title"> Informations étudiants </div>
                </div>
                <div class="row mt-4 fw-normal">
                    <!-- Contenu du tableau de bord -->
                    
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
