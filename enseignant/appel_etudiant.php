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

// Récupérer le cours à partir de l'URL
$cours = isset($_GET['cours']) ? $_GET['cours'] : '';

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données avec MySQLi a échoué : " . $conn->connect_error);
}

// Récupérer les étudiants du cours depuis la base de données
$sqlEtudiants = "
    SELECT utilisateur.matricule, profil.nom, profil.prenom, classe.nom AS classe_nom
    FROM utilisateur
    INNER JOIN attribution_cours ON utilisateur.matricule = attribution_cours.utilisateur_matricule
    INNER JOIN cours ON attribution_cours.cours_id = cours.id
    INNER JOIN classe ON utilisateur.classe_id = classe.id
    INNER JOIN profil ON utilisateur.matricule = profil.utilisateur_matricule
    WHERE cours.nom = ?;
";

$stmtEtudiants = $conn->prepare($sqlEtudiants);
$stmtEtudiants->bind_param('s', $cours);
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
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Matricule</th>
                                <th scope="col">Classe</th>
                                <th scope="col">Présent</th>
                                <th scope="col">Absent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($etudiants as $index => $etudiant) : ?>
                                <tr>
                                    <th scope="row"><?= $index + 1 ?></th>
                                    <td><?= $etudiant['nom'] ?></td>
                                    <td><?= $etudiant['matricule'] ?></td>
                                    <td><?= $etudiant['classe_nom'] ?></td>
                                    <td><input type="checkbox" name="present_<?= $index ?>" value="present"></td>
                                    <td><input type="checkbox" name="absent_<?= $index ?>" value="absent"></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-2 border-start border-secondary">
                <?php include '../templates/user-card.php'; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
