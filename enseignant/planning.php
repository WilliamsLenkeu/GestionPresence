<?php
// Démarrez la session pour accéder aux variables de session
session_start();

// Vérifiez si le matricule est présent dans la session
if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page index.php du dossier parent
    header('Location: ../logout.php');
    exit;
}

// Vérifier si l'utilisateur a un profil
include '../connexion.php';

$matricule = $_SESSION['matricule'];

$sql = "SELECT * FROM information_enseignant WHERE utilisateur_matricule = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $matricule);
$stmt->execute();
$stmt->store_result();

$isAdmin = false;

if ($stmt->num_rows == 0) {
    // L'utilisateur n'a pas de profil, vérifier s'il est administrateur
    $isAdmin = true;
} else {
    // L'utilisateur a un profil, récupérer ses informations
    $stmt->bind_result($utilisateur_matricule, $specialite, $nom, $prenom, $date_naissance, $bureau, $cours_id);
    $stmt->fetch();
}

?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de Bord - <?php echo $isAdmin ? 'Administrateur' : 'Enseignant'; ?></title>
    <!-- Inclure le CSS de jQuery UI pour le DatePicker -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Inclure le CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Inclure votre propre fichier de style -->
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
                    <div class="fs-2 mt-3"> Planning De Cours </div>
                </div>
                <!-- Ajouter un input pour sélectionner la date -->
                <label for="datepicker">Sélectionner une date : </label>
                <input type="text" id="datepicker" readonly>

                <!-- Ajouter un espace pour afficher les cours -->
                <div id="cours-container"></div>
            </div>
            <!-- Colonne de droite pour les informations de l'utilisateur -->
            <div class="col-md-2 border-start border-secondary">
                <?php include './user-card.php'; ?>
            </div>
        </div>
    </div>

    <!-- Inclure le script pour initialiser le Datepicker et gérer les cours -->
    <script>
        $(document).ready(function() {
            // Initialiser le Datepicker
            $("#datepicker").datepicker({
                dateFormat: "yy-mm-dd",
                onSelect: function(selectedDate) {
                    // Gérer la sélection de la date
                    fetchCalendarEvents(selectedDate);
                }
            });
        });

        // Fonction pour récupérer les cours en fonction de la date sélectionnée
        function fetchCalendarEvents(selectedDate) {
            <?php
            // Implémentez la logique pour récupérer les cours en fonction de la date sélectionnée
            // Utilisez une requête SQL pour récupérer les cours en fonction de la date
            // ...

            // Exemple de structure de cours (à remplacer par la logique appropriée)
            $cours = array(
                array(
                    'title' => 'Cours 1',
                    'start' => '2024-01-02T10:00:00',
                    'end' => '2024-01-02T12:00:00'
                ),
                array(
                    'title' => 'Cours 2',
                    'start' => '2024-01-03T14:00:00',
                    'end' => '2024-01-03T16:00:00'
                )
            );

            // Afficher les cours au format JSON
            echo "displayCalendarEvents(" . json_encode($cours) . ");";
            ?>
        }

        // Fonction pour afficher les cours dans le conteneur dédié
        function displayCalendarEvents(cours) {
            var coursContainer = document.getElementById('cours-container');
            coursContainer.innerHTML = ""; // Effacer le contenu précédent

            cours.forEach(function(event) {
                // Créer un élément pour afficher le cours
                var coursElement = document.createElement('div');
                coursElement.innerHTML = "<strong>" + event.title + "</strong><br>De " + event.start + " à " + event.end;
                coursContainer.appendChild(coursElement);
            });
        }
    </script>

    <!-- Inclure le script jQuery et Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
