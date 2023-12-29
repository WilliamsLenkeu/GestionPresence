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
$selectedRole = $_POST['role'];

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

// Vérifier s'il y a un enseignant administrateur dans la table utilisateur
$sqlCheckAdminEnseignant = "SELECT COUNT(*) AS adminEnseignantCount FROM utilisateur WHERE role = 'enseignant' AND administrateur = 1";
$resultCheckAdminEnseignant = $conn->query($sqlCheckAdminEnseignant);

if ($resultCheckAdminEnseignant && $resultCheckAdminEnseignant->num_rows > 0) {
    $rowAdminEnseignant = $resultCheckAdminEnseignant->fetch_assoc();
    $adminEnseignantCount = $rowAdminEnseignant['adminEnseignantCount'];

    if ($adminEnseignantCount > 0 || $selectedRole !== 'etudiant') {
        // Il y a déjà un enseignant administrateur ou l'utilisateur n'est pas un étudiant, utiliser le rôle choisi par l'utilisateur
        $role = $selectedRole;
        $administrateur = 0; // On suppose que l'utilisateur n'est pas administrateur
    } else {
        // Aucun enseignant n'est trouvé, et l'utilisateur veut s'inscrire en tant qu'étudiant
        echo "Erreur: Aucun enseignant n'est disponible. L'inscription en tant qu'étudiant n'est pas autorisée.";
        exit;
    }
} else {
    // En cas d'erreur, afficher un message d'erreur
    echo "Erreur lors de la vérification de l'enseignant administrateur.";
    exit;
}

// Insérer les données dans la table utilisateur
$sql = "INSERT INTO utilisateur (matricule, username, password, role, administrateur) VALUES ('$newMatricule', '$newUsername', '$hashedPassword', '$role', '$administrateur')";

if ($conn->query($sql) === TRUE) {
    // Rediriger vers le formulaire de connexion
    header('Location: index.php');
    exit;
} else {
    echo "Erreur: " . $conn->error;
}

// Fermer la connexion
$conn->close();
?>
