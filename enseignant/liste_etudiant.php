<?php
// Démarrez la session pour accéder aux variables de session
session_start();

// Inclure le fichier de connexion à la base de données
include '../connexion.php';

// Vérifiez si le matricule est présent dans la session
if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page index.php du dossier parent
    header('Location: ../logout.php');
    exit;
}

// Vérifier si l'utilisateur est à la fois enseignant et administrateur
$estEnseignant = isset($_SESSION['role']) && $_SESSION['role'] == 'enseignant';
$estAdministrateur = isset($_SESSION['administrateur']) && $_SESSION['administrateur'] == 1;

if (!($estEnseignant || $estAdministrateur)) {
    // Rediriger vers une page d'erreur ou restreindre l'accès si l'utilisateur n'est pas enseignant administrateur
    header('Location: ../erreur.php');
    exit;
}

// Fonction pour afficher les boutons d'ajout, de modification et de suppression
function afficherBoutonsActions()
{
    echo '<div class="mb-3">';
    echo '<a href="ajouter_etudiant.php" class="btn btn-success">Ajouter un étudiant</a>';
    echo '</div>';
}

// Requête SQL pour récupérer la liste des étudiants depuis la base de données
$sql = "SELECT utilisateur_matricule, nom, classe, date_naissance FROM profil";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);


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

        <!-- Section du tableau de bord -->
        <div class="row dash-1">
            <div class="col-md-2 border-end border-secondary text-center">
                <?php
                    include './navbar.php';
                ?>
            </div>
            <div class="row mt-4 fw-normal">
                <?php
                // Afficher les boutons d'actions pour un enseignant administrateur
                // if ($estAdministrateur) {
                //     afficherBoutonsActions();
                // }
                ?>
                <div class="mb-3 form-inline">
                    <label for="sortOptions" class="form-label mr-2">Trier par :</label>
                    <select class="form-select" id="sortOptions">
                        <option value="classe">Classe</option>
                        <option value="age">Âge</option>
                    </select>
                </div>

                <!-- Tableau responsive -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Classe</th>
                                <th>Date de Naissance</th>
                                <?php
                                // Ajouter une colonne d'actions si l'utilisateur est enseignant administrateur
                                if ($estAdministrateur) {
                                    echo '<th>Actions</th>';
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Afficher la liste des étudiants
                            foreach ($etudiants as $index => $etudiant) {
                                echo '<tr>';
                                echo '<td>' . ($index + 1) . '</td>';
                                echo '<td>' . $etudiant['matricule'] . '</td>';
                                echo '<td>' . $etudiant['nom'] . '</td>';
                                echo '<td>' . $etudiant['classe'] . '</td>';
                                echo '<td>' . $etudiant['date_naissance'] . '</td>';
                                if ($estAdministrateur) {
                                    // Ajouter des boutons d'actions pour un enseignant administrateur
                                    echo '<td>';
                                    echo '<a href="modifier_etudiant.php?matricule=' . $etudiant['matricule'] . '" class="btn btn-warning">Modifier</a>';
                                    echo '<a href="supprimer_etudiant.php?matricule=' . $etudiant['matricule'] . '" class="btn btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet étudiant ?\')">Supprimer</a>';
                                    echo '</td>';
                                }
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
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

    <!-- Script pour la recherche et le tri -->
    <script src="../js/recherche-tri.js"></script>
</body>

</html>
