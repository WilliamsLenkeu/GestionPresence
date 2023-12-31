<?php
session_start();
include '../connexion.php';

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données avec MySQLi a échoué : " . $conn->connect_error);
}

// Utilisation d'une requête préparée pour éviter les attaques par injection SQL
$sql = "SELECT administrateur FROM utilisateur WHERE matricule = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $_SESSION['matricule']);
$stmt->execute();
$stmt->store_result();

// Vérifier si l'utilisateur est administrateur
$isAdmin = false;
if ($stmt->num_rows > 0) {
    $stmt->bind_result($adminStatus);
    $stmt->fetch();
    $isAdmin = $adminStatus == 1;
}

// Fonction pour afficher les boutons d'ajout, de modification et de suppression
function afficherBoutonsActions()
{
    echo '<div class="mb-3">';
    echo '<a href="ajout_cours.php" class="btn btn-success">Ajouter un Cours</a>';
    echo '</div>';
}

?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste De Cours</title>
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
            <div class="col-md col-12">
                <div class="row page-title shadow-lg">
                    <div class="fs-2 mt-3"> Liste Des Cours </div>
                </div>
                <div class="row mt-4 fw-normal">
                    <?php
                    // Afficher les boutons d'actions pour un enseignant administrateur
                    if ($isAdmin) {
                        afficherBoutonsActions();
                    }
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom du Cours</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Facultatif</th>
                                    <th scope="col">Nom de la Classe</th>
                                    <?php
                                    // Ajouter une colonne pour les actions si l'utilisateur est administrateur
                                    if ($isAdmin) {
                                        echo '<th scope="col">Actions</th>';
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                // Afficher la liste des cours
                                $sqlCours = "SELECT id, nom, description, facultatif, classe_id FROM cours";
                                $resultCours = $conn->query($sqlCours);

                                    if ($resultCours->num_rows > 0) {
                                        while ($rowCours = $resultCours->fetch_assoc()) {
                                            echo '<tr class="fs-5">';
                                            echo '<th scope="row">' . $rowCours['id'] . '</th>';
                                            echo '<td>' . $rowCours['nom'] . '</td>';
                                            echo '<td>' . $rowCours['description'] . '</td>';
                                            echo '<td>' . ($rowCours['facultatif'] ? 'Oui' : 'Non') . '</td>';

                                            // Récupérer le nom de la classe associée
                                            $classe_id = $rowCours['classe_id'];
                                            $sqlNomClasse = "SELECT nom FROM classe WHERE id = $classe_id";
                                            $resultNomClasse = $conn->query($sqlNomClasse);
                                            $rowNomClasse = $resultNomClasse->fetch_assoc();

                                            echo '<td>' . $rowNomClasse['nom'] . '</td>';

                                            // Ajouter des boutons d'action si l'utilisateur est administrateur
                                            if ($isAdmin) {
                                                echo '<td>';
                                                echo '<div class="btn-group" role="group" aria-label="Actions">';
                                                echo '<button type="button" class="btn btn-warning">Modifier</button>';
                                                echo '<button type="button" class="btn btn-danger">Supprimer</button>';
                                                echo '</div>';
                                                echo '</td>';
                                            }

                                            echo '</tr>';
                                        }
                                    } else {
                                        // Aucun cours trouvé dans la base de données
                                        echo '<tr>';
                                        echo '<td colspan="6" class="text-center">Aucun cours existant.</td>';
                                        echo '</tr>';
                                    }
                                ?>

                            </tbody>
                        </table>
                    </div>
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