<?php
// Démarrez la session pour accéder aux variables de session
session_start();

// Vérifiez si le matricule est présent dans la session
if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page index.php du dossier parent
    header('Location: ../logout.php');
    exit;
}

// Vérifier si l'enseignant a un profil
include '../connexion.php';

$matricule = $_SESSION['matricule'];

$sql = "SELECT * FROM profil WHERE utilisateur_matricule = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $matricule);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    // L'enseignant n'a pas de profil, rediriger vers la page de profil
    header('Location: remplir_profil_enseignant.php');
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
                </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
