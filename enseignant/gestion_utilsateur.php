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

$sql = "SELECT * FROM information_enseignant WHERE utilisateur_matricule = ?";
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
        <div class="row dash-1">
            <div class="col-md-2 border-end border-secondary text-center">
                <?php include './navbar.php'; ?>
            </div>
            <div class="col-md col-12">
                <div class="row page-title shadow-lg">
                    <div class="fs-2 mt-3"> Gestion Utilisateur </div>
                </div>
                <div class="row mt-4 fw-normal">
                    <div class="col-md-12">
                        <!-- Affichage de la liste des utilisateurs enseignants -->
                        <h4>Liste des Utilisateurs Enseignants</h4>

                        <?php
                        // Sélectionnez les utilisateurs enseignants (sauf le premier compte admin)
                        $sql = "SELECT matricule, username, administrateur FROM utilisateur WHERE role = 'enseignant' AND matricule != ? ORDER BY username";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $matricule);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Vérifiez s'il y a des utilisateurs enseignants
                        if ($result->num_rows > 0) {
                            // Affiche la liste des utilisateurs enseignants
                            echo '<table class="table table-bordered">';
                            echo '<thead><tr><th>Matricule</th><th>Username</th><th>Administrateur</th><th>Action</th></tr></thead>';
                            echo '<tbody>';
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row['matricule'] . '</td>';
                                echo '<td>' . $row['username'] . '</td>';
                                echo '<td>' . ($row['administrateur'] ? 'Oui' : 'Non') . '</td>';
                                echo '<td>';
                                // Formulaire pour soumettre les informations à update_admin_status.php
                                echo '<form action="update_admin_status.php" method="post" class="form-check form-switch">';
                                echo '<input type="hidden" name="matricule" value="' . $row['matricule'] . '">';
                                echo '<input type="checkbox" name="isAdmin" class="form-check-input" ' . ($row['administrateur'] ? 'checked' : '') . ' onchange="this.form.submit()">';
                                echo '<label class="form-check-label">Administrateur</label>';
                                echo '</form>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            // Aucun compte enseignant existant
                            echo '<p class="text-muted">Aucun compte enseignant existant.</p>';
                        }
                        ?>
                    </div>
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
