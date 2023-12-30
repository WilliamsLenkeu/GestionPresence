<?php
// Démarrez la session pour accéder aux variables de session
session_start();

// Vérifiez si le matricule est présent dans la session
if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page de déconnexion
    header('Location: ../logout.php');
    exit;
}

// Connexion à la base de données (à adapter selon votre configuration)
include '../connexion.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupérer le matricule de l'enseignant à partir de la session
$matricule = $_SESSION['matricule'];

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $specialite = $_POST['specialite'];
    $bureau = $_POST['bureau'];

    // Utilisation d'une requête préparée pour éviter les attaques par injection SQL
    $sql_profil = "INSERT INTO profil (utilisateur_matricule, nom, prenom, date_naissance) VALUES (?, ?, ?, ?)";
    $stmt_profil = $conn->prepare($sql_profil);
    $stmt_profil->bind_param('ssss', $matricule, $nom, $prenom, $date_naissance);

    // Exécuter la requête pour la table profil
    if ($stmt_profil->execute()) {
        // Enregistrement dans la table information_enseignant
        $sql_info_enseignant = "INSERT INTO information_enseignant (utilisateur_matricule, specialite, bureau) VALUES (?, ?, ?)";
        $stmt_info_enseignant = $conn->prepare($sql_info_enseignant);
        $stmt_info_enseignant->bind_param('sss', $matricule, $specialite, $bureau);

        // Exécuter la requête pour la table information_enseignant
        if ($stmt_info_enseignant->execute()) {
            // Rediriger vers le tableau de bord de l'enseignant après l'insertion
            header('Location: dashboard_enseignant.php');
            exit;
        } else {
            // Gérer les erreurs d'insertion pour information_enseignant
            echo 'Erreur lors de l\'insertion dans la table information_enseignant : ' . $stmt_info_enseignant->error;
        }

        // Fermer la connexion pour information_enseignant
        $stmt_info_enseignant->close();
    } else {
        // Gérer les erreurs d'insertion pour profil
        echo 'Erreur lors de l\'insertion dans la table profil : ' . $stmt_profil->error;
    }

    // Fermer la connexion pour profil
    $stmt_profil->close();
}

// Fermer la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remplir Profil Enseignant</title>
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

<body class="container-fluid">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="blur-background form-container">
            <h2 class="mb-4 text-center">Remplir le Profil Enseignant</h2>
            <form method="POST" action="" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom :</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom :</label>
                    <input type="text" name="prenom" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="date_naissance" class="form-label">Date de Naissance :</label>
                    <input type="date" name="date_naissance" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="specialite" class="form-label">Spécialité :</label>
                    <input type="text" name="specialite" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="bureau" class="form-label">Bureau :</label>
                    <input type="text" name="bureau" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
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
