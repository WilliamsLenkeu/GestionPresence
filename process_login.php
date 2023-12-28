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
$sql = "SELECT matricule, username, password FROM utilisateur WHERE matricule = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $matricule);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($storedMatricule, $storedUsername, $storedPassword);
    $stmt->fetch();

    // Vérifier si le nom d'utilisateur et le mot de passe correspondent
    if ($username === $storedUsername && password_verify($password, $storedPassword)) {
        // Authentification réussie, rediriger vers la page d'accueil ou le tableau de bord
        header('Location: accueil.php');
        exit;
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
