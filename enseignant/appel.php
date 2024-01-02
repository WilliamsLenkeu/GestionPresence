<?php
// Démarrez la session pour accéder aux variables de session
session_start();

// Vérifiez si le matricule est présent dans la session
if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page index.php du dossier parent
    header('Location: ../logout.php');
    exit;
}

// Inclure le fichier de connexion à la base de données
include '../connexion.php';

// Requête SQL pour récupérer la liste des cours avec les enseignants associés
$sql = "SELECT cours.nom AS nom_cours, utilisateur.username AS enseignant
        FROM cours
        LEFT JOIN attribution_cours ON cours.id = attribution_cours.cours_id
        LEFT JOIN utilisateur ON attribution_cours.utilisateur_matricule = utilisateur.matricule";

// Préparation de la requête
$stmt = $pdo->prepare($sql);

// Exécution de la requête
$stmt->execute();

// Récupération des résultats de la requête
$cours = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Appel</title>
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
                    <div class="fs-2 mt-3"> Listes D'appels </div>
                </div>

                <!-- Formulaire de recherche en temps réel -->

                <!-- Liste des cours dynamique -->
                <ul class="list-group" id="coursList">
                    <?php
                    if (empty($cours)) {
                        // Aucun cours trouvé dans la base de données
                        echo '<li class="list-group-item text-center my-3">Aucun cours existant.</li>';
                    } else {
                        echo '<form class="row mt-4 fw-normal">';
                        echo '<div class="input-group mb-3">';
                        echo '<input type="text" class="form-control shadow-sm" placeholder="Rechercher un cours" name="search" id="searchInput">';
                        echo '</div>';
                        echo '</form>';
                        // Afficher la liste des cours
                        foreach ($cours as $index => $c) {
                            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                            echo '<span>' . ($index + 1) . '. ' . $c['nom_cours'] . '</span>';
                            echo '<span class="ms-3">Enseignant: ' . $c['enseignant'] . '</span>';
                            echo '<a href="appel_etudiant.php?cours=' . urlencode($c['nom_cours']) . '" class="btn btn-primary btn-sm">Faire l\'appel</a>';
                            echo '</li>';
                        }
                    }
                    ?>
                </ul>

            </div>
            <div class="col-md-2 border-start border-secondary">
                <?php include './user-card.php'; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Script JavaScript pour la recherche en temps réel -->
    <script>
        // Fonction pour mettre à jour la liste des cours en fonction de la recherche
        function updateCoursList(search) {
            // Filtrer les cours en fonction de la recherche
            var coursFiltres = <?php echo json_encode($cours); ?>;
            if (search) {
                coursFiltres = coursFiltres.filter(function (c) {
                    return c.nom_cours.toLowerCase().includes(search.toLowerCase()) || c.enseignant.toLowerCase().includes(search.toLowerCase());
                });
            }

            // Afficher les cours filtrés
            var coursListElement = document.getElementById('coursList');
            coursListElement.innerHTML = '';
            coursFiltres.forEach(function (c, index) {
                var listItem = document.createElement('li');
                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                listItem.innerHTML = '<span>' + (index + 1) + '. ' + c.nom_cours + '</span>' +
                    '<span class="ms-3">Enseignant: ' + c.enseignant + '</span>' +
                    '<a href="appel_etudiant.php?cours=' + encodeURIComponent(c.nom_cours) + '" class="btn btn-primary btn-sm">Faire l\'appel</a>';
                coursListElement.appendChild(listItem);
            });
        }

        // Écouter les changements dans le champ de recherche
        document.getElementById('searchInput').addEventListener('input', function () {
            var searchInputValue = this.value;
            updateCoursList(searchInputValue);
        });
    </script>
</body>

</html>
