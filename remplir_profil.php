<?php

if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page de déconnexion
    header('Location: ../logout.php');
    exit;
}

// Inclure le fichier de connexion à la base de données
include 'connexion.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Connexion à la base de données
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion à la base de données
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    // Récupérer les données du formulaire
    $matricule = $_POST['matricule'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Utiliser une requête préparée pour éviter les attaques par injection SQL
    $sql = "SELECT u.matricule, u.username, u.password, u.role, p.id
            FROM utilisateur u
            LEFT JOIN profil p ON u.matricule = p.utilisateur_matricule
            WHERE u.matricule = ? LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $matricule);
    $stmt->execute();
    $stmt->store_result();

    // Vérifier si l'utilisateur existe
    if ($stmt->num_rows > 0) {
        // Récupérer les données de l'utilisateur
        $stmt->bind_result($storedMatricule, $storedUsername, $storedPassword, $role, $profileId);
        $stmt->fetch();

        // Vérifier le mot de passe
        if ($username === $storedUsername && password_verify($password, $storedPassword)) {
            // Authentification réussie, rediriger en fonction du rôle
            switch ($role) {
                case 'etudiant':
                    header('Location: tableau_de_bord_etudiant.php');
                    exit;
                    break;

                case 'enseignant':
                    header('Location: enseignant/dashboard_enseignant.php');
                    exit;
                    break;
            }
        } else {
            // Mot de passe incorrect
            echo '<script>alert("Mot de passe incorrect."); window.location.replace("index.php");</script>';
        }
    } else {
        // Matricule non trouvé
        echo '<script>alert("Matricule non trouvé."); window.location.replace("index.php");</script>';
    }

    // Fermer la requête et la connexion à la base de données
    $stmt->close();
    $conn->close();
} else {
    // Rediriger vers la page d'accueil si la soumission du formulaire n'est pas correcte
    header('Location: index.php');
    exit;
}
?>
