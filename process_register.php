<?php
// Connexion à la base de données (à adapter selon votre configuration)
include './connexion.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupérer les données du formulaire
$newMatricule = $_POST['newMatricule'];
$newUsername = $_POST['newUsername'];
$newPassword = $_POST['newPassword'];
$role = $_POST['role'];

// Vérifier si le matricule existe déjà
$sqlCheckMatricule = "SELECT COUNT(*) AS matriculeCount FROM utilisateur WHERE matricule = '$newMatricule'";
$resultCheckMatricule = $conn->query($sqlCheckMatricule);

if ($resultCheckMatricule && $resultCheckMatricule->num_rows > 0) {
    $row = $resultCheckMatricule->fetch_assoc();
    $matriculeCount = $row['matriculeCount'];

    if ($matriculeCount > 0) {
        // Le matricule existe déjà, afficher un message d'erreur ou rediriger vers le formulaire d'inscription avec un message d'erreur
        echo "Erreur: Le matricule existe déjà.";
        exit;
    }
}

// Hasher le mot de passe (à adapter selon vos besoins)
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Insérer les données dans la table utilisateur
$sql = "INSERT INTO utilisateur (matricule, username, password, role) VALUES ('$newMatricule', '$newUsername', '$hashedPassword', '$role')";

if ($conn->query($sql) === TRUE) {
    // Rediriger vers le formulaire de connexion
    header('Location: nom_de_votre_page_de_connexion.php');
    exit;
} else {
    echo "Erreur: " . $conn->error;
}

// Fermer la connexion
$conn->close();
?>
