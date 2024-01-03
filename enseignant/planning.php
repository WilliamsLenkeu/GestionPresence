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

// Récupération des cours alloués à l'enseignant pour chaque jour de la semaine
$joursSemaine = []; // Tableau des jours de la semaine
$coursParJour = []; // Tableau associatif pour stocker les cours par jour

// Remplacez les requêtes SQL suivantes par vos propres requêtes pour récupérer les données depuis la base de données
// Assurez-vous d'avoir une jointure appropriée entre les tables pour récupérer les cours alloués à l'enseignant

// Exemple de requête SQL pour récupérer tous les jours de la semaine
$sqlJoursSemaine = "SELECT * FROM jour_semaine";

// Exemple de requête SQL pour récupérer les cours par jour
$sqlCoursParJour = "SELECT cours.nom AS nom_cours, jour_semaine.nom_jour, planning_cours_jour.heure_debut, planning_cours_jour.heure_fin
                   FROM planning_cours_jour
                   INNER JOIN cours ON planning_cours_jour.cours_id = cours.id
                   INNER JOIN jour_semaine ON planning_cours_jour.jour_id = jour_semaine.id
                   WHERE cours.classe_id IN (SELECT classe_id FROM utilisateur WHERE matricule = :matricule)
                   AND jour_semaine.id = :jour_id";

// Exécution des requêtes SQL avec PDO (utilisation de la connexion existante)
try {
    // Récupération de tous les jours de la semaine
    $stmtJoursSemaine = $pdo->prepare($sqlJoursSemaine);
    $stmtJoursSemaine->execute();
    $joursSemaine = $stmtJoursSemaine->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des cours par jour
    foreach ($joursSemaine as $jour) {
        $stmtCoursParJour = $pdo->prepare($sqlCoursParJour);
        $stmtCoursParJour->bindParam(':matricule', $matricule, PDO::PARAM_INT);
        $stmtCoursParJour->bindParam(':jour_id', $jour['id'], PDO::PARAM_INT);
        $stmtCoursParJour->execute();
        $coursParJour[$jour['nom_jour']] = $stmtCoursParJour->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planning De Cours - <?php echo $matricule; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css" type="text/css" />
    <link rel="icon" href="../image/logo.jpg" type="image/x-icon">
</head>

<body class="fw-bold">
    <div class="container-fluid dash">
        <!-- Section du tableau de bord -->
        <div class="row dash-1">
            <div class="col-md-2 border-end border-secondary text-center">
                <?php include './navbar.php'; ?>
            </div>
            <div class="col-md col-12">
                <div class="row page-title shadow-lg mb-4">
                    <div class="col">
                        <h2 class="fs-2 mt-3">Planning De Cours - <?php echo $matricule; ?></h2>
                    </div>
                </div>
                <!-- Afficher le planning des cours liés à l'enseignant -->
                <div class="container mt-3">
                    <h4>Planning des cours liés à l'enseignant</h4>
                    <?php
                    foreach ($joursSemaine as $jour) {
                        echo "<div class='card mb-3'>";
                        echo "<div class='card-header'><strong>{$jour['nom_jour']}</strong></div>";
                        echo "<div class='card-body'>";
                        if (isset($coursParJour[$jour['nom_jour']])) {
                            foreach ($coursParJour[$jour['nom_jour']] as $cours) {
                                echo "<p class='card-text'>{$cours['nom_cours']} de {$cours['heure_debut']} à {$cours['heure_fin']}</p>";
                            }
                        } else {
                            echo "<p class='card-text'>Aucun cours planifié pour l'instant.</p>";
                        }
                        echo "</div></div>";
                    }
                    ?>
                </div>
            </div>
            <!-- Colonne de droite pour les informations de l'utilisateur -->
            <div class="col-md-2 border-start border-secondary">
                <?php include './user-card.php'; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>