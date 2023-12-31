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
    // L'etudiant n'a pas de profil, rediriger vers la page de profil
    header('Location: remplir_profil_etudiant.php');
    exit;
}

// Récupérer la liste des étudiants avec leur matricule et la somme de leurs heures d'absence
$sqlListeEtudiants = "SELECT u.matricule, p.nom, p.prenom, COALESCE(SUM(ea.heures_attribuees), 0) AS total_heures_absence
                     FROM utilisateur u
                     JOIN profil p ON u.matricule = p.utilisateur_matricule
                     LEFT JOIN enregistrement_assiduite ea ON u.matricule = ea.utilisateur_matricule
                     GROUP BY u.matricule, p.nom, p.prenom";
$resultListeEtudiants = $conn->query($sqlListeEtudiants);

?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de Bord - Enseignant</title>
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
                    <!-- Liste des étudiants avec la somme de leurs heures d'absence -->
                    <?php
                    if ($resultListeEtudiants->num_rows > 0) {
                        while ($rowEtudiant = $resultListeEtudiants->fetch_assoc()) {
                            echo '<div class="col-md-4 mb-4">';
                            echo '<div class="card">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $rowEtudiant['nom'] . ' ' . $rowEtudiant['prenom'] . '</h5>';
                            echo '<p class="card-text">Matricule : ' . $rowEtudiant['matricule'] . '</p>';
                            echo '<p class="card-text">Total heures d\'absence : ' . $rowEtudiant['total_heures_absence'] . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="col-md-12">';
                        echo '<p>Aucun étudiant trouvé.</p>';
                        echo '</div>';
                    }
                    ?>
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
