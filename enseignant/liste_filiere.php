<?php
// Démarrez la session pour accéder aux variables de session
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
    echo '<a href="ajout_filiere.php" class="btn btn-success">Ajouter une Classe</a>';
    echo '</div>';
}

// Requête SQL pour récupérer la liste des classes
$sql = "SELECT id, nom, description FROM classe";
$result = $conn->query($sql);
$classes = $result->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste Des Classes</title>
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
                    <div class="fs-2 mt-3"> Liste Des Classes </div>
                </div>
                <div class="row mt-4 fw-normal">
                    <!-- Ajout de boutons pour les actions sur les classes -->
                    <?php
                    // Afficher les boutons d'actions pour un administrateur
                    if ($isAdmin) {
                        afficherBoutonsActions();
                    }
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Afficher la liste des classes
                                if (empty($classes)) {
                                    // Aucune classe trouvée dans la base de données
                                    echo '<ul class="list-group" >';
                                    echo '<li class="list-group-item text-center">Aucune classe existante.</li>';
                                    echo '</ul>';
                                } else {
                                    foreach ($classes as $index => $classeInfo) {
                                        echo '<tr>';
                                        echo '<td>' . ($index + 1) . '</td>';
                                        echo '<td>' . $classeInfo['nom'] . '</td>';
                                        echo '<td>' . $classeInfo['description'] . '</td>';
                                        echo '<td><a href="liste_cours.php?id_classe=' . $classeInfo['id'] . '" class="btn btn-info btn-sm">Cours</a></td>';
                                        // Ajouter des boutons d'action pour un administrateur
                                        if($isAdmin){
                                            echo '<td>';
                                            // echo '<a href="modifier_filiere.php?id=' . $classeInfo['id'] . '" class="btn btn-warning btn-sm">Modifier</a>';
                                            echo ' ';
                                            echo '<a href="supprimer_filiere.php?id=' . $classeInfo['id'] . '" class="btn btn-danger btn-sm">Supprimer</a>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    }
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
                    include './user-card.php';
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
