<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['matricule'])) {
    header('Location: index.php');
    exit;
}

// Connexion à la base de données (à adapter selon votre configuration)
include '../connexion.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupérer le matricule de l'utilisateur depuis la session
$matricule = $_SESSION['matricule'];

// Récupérer la classe_id depuis la table information_etudiant
$sqlGetClasseId = "SELECT classe_id FROM information_etudiant WHERE utilisateur_matricule = ?";
$stmtGetClasseId = $conn->prepare($sqlGetClasseId);
$stmtGetClasseId->bind_param('s', $matricule);
$stmtGetClasseId->execute();
$stmtGetClasseId->bind_result($classe_id);
$stmtGetClasseId->fetch();
$stmtGetClasseId->close();

// Vérifier si l'étudiant a déjà rempli ses cours facultatif
$sqlCheckProfil = "SELECT utilisateur_matricule FROM attribution_cours WHERE utilisateur_matricule = ?";
$stmtCheckProfil = $conn->prepare($sqlCheckProfil);
$stmtCheckProfil->bind_param('s', $matricule);
$stmtCheckProfil->execute();
$stmtCheckProfil->store_result();

// Si des données existent, rediriger vers le tableau de bord de l'étudiant
if ($stmtCheckProfil->num_rows > 0) {
    header('Location: dashboard_etudiant.php');
    exit;
}

// Fermer la déclaration
$stmtCheckProfil->close();

// Récupérer la liste des cours facultatifs de la classe
$sqlCoursFacultatifs = "SELECT id, nom FROM cours WHERE classe_id = ? AND facultatif = 1";
$stmtCoursFacultatifs = $conn->prepare($sqlCoursFacultatifs);
$stmtCoursFacultatifs->bind_param('s', $classe_id);
$stmtCoursFacultatifs->execute();
$resultCoursFacultatifs = $stmtCoursFacultatifs->get_result();

// Fermer la connexion
$conn->close();
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remplir Profil Etudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-image: url('../image/background.jpeg');
            background-repeat: no-repeat;
            background-size: cover;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .blur-background {
            background-color: rgba(255, 255, 255, 0.8);
            border: none;
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 10px;
        }

        .form-container {
            max-width: 400px;
        }

        .form-label,
        .form-control {
            color: #444;
        }

        .btn-primary {
            background-color: #444;
            border: none;
        }

        .btn-primary:hover {
            background-color: #666;
        }
    </style>
</head>

<body class="fw-bold">
    <div class="container-fluid dash d-flex justify-content-center align-items-center vh-100">
        <!-- Ajoutez votre formulaire ici -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="blur-background form-container needs-validation" novalidate>
            <!-- Insérez ici les champs du formulaire (nom, prénom, date de naissance, classe, etc.) -->
            <h2 class="mb-4 text-center">Remplir le Profil Etudiant</h2>
            <div class="mb-3">
                <!-- Afficher la liste des cours facultatifs sous forme de cases à cocher -->
                <?php
                while ($row = $resultCoursFacultatifs->fetch_assoc()) {
                    echo '<div class="form-check">';
                    echo '<input class="form-check-input" type="checkbox" name="cours[]" value="' . $row['id'] . '" id="cours' . $row['id'] . '">';
                    echo '<label class="form-check-label" for="cours' . $row['id'] . '">' . $row['nom'] . '</label>';
                    echo '</div>';
                }
                ?>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Validation Script -->
    <script>
        // Désactiver la validation native du formulaire
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>

</html>
