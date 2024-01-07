<?php
// Démarrez la session pour accéder aux variables de session
session_start();

include '../connexion.php';

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données avec MySQLi a échoué : " . $conn->connect_error);
}

// Récupérer l'identifiant de la classe depuis le paramètre d'URL
$classeId = $_GET['id_classe'];

// Requête SQL pour récupérer la liste des cours liés à la classe
$sql = "SELECT c.id, c.nom, c.description, c.facultatif
        FROM cours c
        WHERE c.classe_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $classeId);
$stmt->execute();
$result = $stmt->get_result();
$cours = $result->fetch_all(MYSQLI_ASSOC);

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
                            foreach ($cours as $rowCours) {
                                echo '<tr class="fs-5">';
                                echo '<th scope="row">' . $rowCours['id'] . '</th>';
                                echo '<td>' . $rowCours['nom'] . '</td>';
                                echo '<td>' . $rowCours['description'] . '</td>';
                                echo '<td>' . ($rowCours['facultatif'] ? 'Oui' : 'Non') . '</td>';

                                // Ajouter des boutons d'action si l'utilisateur est administrateur
                                if ($isAdmin) {
                                    echo '<td>';
                                    // echo '<a href="modifier_cours.php?id=' . $rowCours['id'] . '" class="btn btn-sm btn-warning me-3">Modifier</a>';
                                    echo '<a href="supprimer_cours.php?id=' . $rowCours['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce cours ?\')">Supprimer</a>';
                                    echo '</td>';
                                }

                                echo '</tr>';
                            }

                            if (empty($cours)) {
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
                include './user-card.php';
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
