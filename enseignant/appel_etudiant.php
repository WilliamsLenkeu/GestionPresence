<?php
// Démarrez la session pour accéder aux variables de session
session_start();

// Vérifiez si le matricule est présent dans la session
if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page logout.php du dossier parent
    header('Location: ../logout.php');
    exit;
}

// Inclure le fichier de connexion à la base de données
include '../connexion.php';

// Récupérer le nom du cours à partir de l'URL
$nomCours = isset($_GET['cours']) ? $_GET['cours'] : '';

// Récupérer l'ID du cours
$sqlCoursId = "SELECT id FROM cours WHERE nom = ?";
$stmtCoursId = $conn->prepare($sqlCoursId);
$stmtCoursId->bind_param('s', $nomCours);
$stmtCoursId->execute();
$resultCoursId = $stmtCoursId->get_result();
$coursId = $resultCoursId->fetch_assoc()['id'];

$stmtCoursId->close();

// Récupérer les étudiants du cours depuis la base de données
$sqlEtudiants = "
    SELECT utilisateur.matricule, information_etudiant.nom, information_etudiant.prenom, classe.nom AS classe_nom
    FROM utilisateur
    INNER JOIN attribution_cours ON utilisateur.matricule = attribution_cours.utilisateur_matricule
    INNER JOIN cours ON attribution_cours.cours_id = cours.id
    INNER JOIN information_etudiant ON utilisateur.matricule = information_etudiant.utilisateur_matricule
    INNER JOIN classe ON information_etudiant.classe_id = classe.id
    WHERE cours.id = ? AND attribution_cours.cours_id = cours.id;
";

$stmtEtudiants = $conn->prepare($sqlEtudiants);
$stmtEtudiants->bind_param('i', $coursId);
$stmtEtudiants->execute();
$resultEtudiants = $stmtEtudiants->get_result();

// Charger les résultats dans un tableau
$etudiants = $resultEtudiants->fetch_all(MYSQLI_ASSOC);

$stmtEtudiants->close();
$conn->close();
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Appel Étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css" type="text/css" />
    <link rel="icon" href="../image/logo.jpg" type="image/x-icon">
    <link rel="shortcut icon" href="../image/logo.jpg" type="image/x-icon">
</head>

<body class="fw-bold">
    <div class="container-fluid dash">
        <!-- Section du tableau de bord -->
        <div class="row dash-1">
            <div class="col-md-2 border-end border-secondary text-center">
                <?php include './navbar.php'; ?>
            </div>
            <div class="col-md col-12">
                <div class="row page-title shadow-lg">
                    <div class="fs-2 mt-3"> Liste D'appels - <?php echo isset($_GET['cours']) ? $_GET['cours'] : ''; ?></div>
                </div>
                <div class="row mt-4 fw-normal">
                    <?php if (empty($etudiants)) : ?>
                        <div class="alert alert-warning" role="alert">
                            Aucun étudiant inscrit à ce cours.
                        </div>
                    <?php else : ?>
                        <form method="post" action="enregistrer_presence.php">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nom</th>
                                        <th scope="col">Matricule</th>
                                        <th scope="col">Classe</th>
                                        <th scope="col">Présent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($etudiants as $index => $etudiant) : ?>
                                        <tr>
                                            <th scope="row"><?= $index + 1 ?></th>
                                            <td><?= $etudiant['nom'] . ' ' . $etudiant['prenom'] ?></td>
                                            <td><?= $etudiant['matricule'] ?></td>
                                            <td><?= $etudiant['classe_nom'] ?></td>
                                            <td><input type="checkbox" name="presence[<?= $etudiant['matricule'] ?>]" value="present"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary">Enregistrer la présence</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-2 border-start border-secondary">
                <?php include './user-card.php'; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
