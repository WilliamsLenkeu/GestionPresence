<?php
session_start();

include '../connexion.php';

if (!isset($_SESSION['matricule'])) {
    header('Location: ../logout.php');
    exit;
}

if ($conn->connect_error) {
    die("La connexion à la base de données avec MySQLi a échoué : " . $conn->connect_error);
}

$sql = "SELECT administrateur FROM utilisateur WHERE matricule = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_SESSION['matricule']);
$stmt->execute();
$stmt->bind_result($isAdmin);
$stmt->fetch();
$stmt->close();

function afficherBoutonsActions()
{
    echo '<div class="my-3">';
    echo '<a href="ajouter_etudiant.php" class="btn btn-success">Ajouter un étudiant</a>';
    echo '</div>';
}

$sql = "SELECT u.matricule, p.nom, p.prenom, p.date_naissance, ie.classe_id
        FROM utilisateur u
        JOIN information_etudiant p ON u.matricule = p.utilisateur_matricule
        LEFT JOIN information_etudiant ie ON u.matricule = ie.utilisateur_matricule
        LEFT JOIN attribution_cours ac ON u.matricule = ac.utilisateur_matricule
        LEFT JOIN cours c ON ac.cours_id = c.id
        WHERE u.role = 'etudiant'";
$result = $conn->query($sql);

$etudiants = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste Des Etudiants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css" type="text/css" />
    <link rel="icon" href="../image/logo.jpg" type="image/x-icon">
</head>

<body class="fw-bold">
    <div class="container-fluid dash">
        <div class="row dash-1">
            <div class="col-md-2 border-end border-secondary text-center">
                <?php
                // inclusion du fichier de navigation
                include './navbar.php';
                ?>
            </div>
            <div class="col-md col-12 fw-normal">
                <div class="row page-title shadow-lg">
                    <div class="fs-2 my-4"> Liste Des Etudiants </div>
                </div>
                <?php if (count($etudiants) > 0) : ?>
                    <div class="mb-3 form-inline">
                        <input type="text" class="form-control" id="searchInput" placeholder="Entrez le terme de recherche">
                    </div>

                    <div class="mb-3 form-inline">
                        <label for="sortOptions" class="form-label mr-2">Trier par :</label>
                        <select class="form-select" id="sortOptions">
                            <option value="classe">Classe</option>
                            <option value="age">Âge</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <?php if (count($etudiants) > 0) : ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Matricule</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Date de Naissance</th>
                                    <th>Classe</th>
                                    <?php
                                    if ($isAdmin) {
                                        echo '<th>Actions</th>';
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($etudiants as $index => $etudiant) {
                                    echo '<tr>';
                                    echo '<td>' . ($index + 1) . '</td>';
                                    echo '<td>' . $etudiant['matricule'] . '</td>';
                                    echo '<td>' . $etudiant['nom'] . '</td>';
                                    echo '<td>' . $etudiant['prenom'] . '</td>';
                                    echo '<td>' . $etudiant['date_naissance'] . '</td>';
                                    echo '<td>' . $etudiant['classe_id'] . '</td>';
                                    if ($isAdmin) {
                                        echo '<td>';
                                        echo '<a href="modifier_etudiant.php?matricule=' . $etudiant['matricule'] . '" class="btn btn-warning me-3">Modifier</a>';
                                        echo '<a href="supprimer_etudiant.php?matricule=' . $etudiant['matricule'] . '" class="btn btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet étudiant ?\')">Supprimer</a>';
                                        echo '</td>';
                                    }
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <ul class="list-group">
                            <li class="list-group-item text-center">Aucun étudiant existant.</li>
                        </ul>
                    <?php endif; ?>
                </div>

            </div>
            <div class="col-md-2 border-start border-secondary">
                <?php
                include '../templates/user-card.php';
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="../js/recherche-tri.js"></script>
</body>

</html>
