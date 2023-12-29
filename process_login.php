<?php
// Connexion à la base de données (à adapter selon votre configuration)
include './connexion.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupérer les données du formulaire
$matricule = $_POST['matricule'];
$username = $_POST['username'];
$password = $_POST['password'];

// Utilisation d'une requête préparée pour éviter les attaques par injection SQL
$sql = "SELECT u.matricule, u.username, u.password, u.role, p.id
        FROM utilisateur u
        LEFT JOIN profil p ON u.matricule = p.utilisateur_matricule
        WHERE u.matricule = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $matricule);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($storedMatricule, $storedUsername, $storedPassword, $role, $profilId);
    $stmt->fetch();

    // Vérifier si le nom d'utilisateur et le mot de passe correspondent
    if ($username === $storedUsername && password_verify($password, $storedPassword)) {
        // Authentification réussie

        // Vérifier si l'utilisateur a un profil
        if ($profilId) {
            // L'utilisateur a un profil, stocker les informations dans les variables de session
            session_start();
            $_SESSION['matricule'] = $storedMatricule;
            $_SESSION['username'] = $storedUsername;
            $_SESSION['role'] = $role;

            // Rediriger vers le tableau de bord correspondant
            switch ($role) {
                case 'etudiant':
                    header('Location: tableau_de_bord_etudiant.php');
                    exit;
                    break;

                case 'enseignant':
                    header('Location: enseignant/dashboard_enseignant.php');
                    exit;
                    break;

                // Ajoutez d'autres cas selon vos besoins (administrateur, etc.)
            }
        } else {
            // L'utilisateur n'a pas de profil, rediriger vers le formulaire de profil
            $_SESSION['matricule'] = $storedMatricule;
            $_SESSION['username'] = $storedUsername;
            $_SESSION['role'] = $role;
            header('Location: remplir_profil.php');
            exit;
        }
    } else {
        // Mot de passe incorrect, afficher une alerte d'erreur avec Bootstrap
        echo '<script>alert("Mot de passe incorrect."); window.location.replace("index.php");</script>';
    }
} else {
    // Matricule non trouvé, afficher une alerte d'erreur avec Bootstrap
    echo '<script>alert("Matricule non trouvé."); window.location.replace("index.php");</script>';
}

// Fermer la connexion
$stmt->close();
$conn->close();
?>
