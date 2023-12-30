<?php
// Inclusion du fichier de connexion à la base de données
require_once("connexion.php");

// Validation du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $newMatricule = $_POST["newMatricule"];
    $newUsername = $_POST["newUsername"];
    $newPassword = $_POST["newPassword"];
    $role = $_POST["role"];

    // Vérification de l'existence du matricule
    $checkMatriculeQuery = "SELECT * FROM utilisateur WHERE matricule = :matricule";
    $checkMatriculeStatement = $pdo->prepare($checkMatriculeQuery);
    $checkMatriculeStatement->bindParam(":matricule", $newMatricule, PDO::PARAM_INT);
    $checkMatriculeStatement->execute();

    if ($checkMatriculeStatement->rowCount() > 0) {
        // Le matricule existe déjà, affichage d'une erreur
        echo "Erreur : Le matricule existe déjà.";
        exit();
    }

    // Hachage du mot de passe
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Vérification de l'existence d'un enseignant administrateur
    $countAdminQuery = "SELECT COUNT(*) FROM utilisateur WHERE role = 'enseignant' AND administrateur = 1";
    $countAdminStatement = $pdo->query($countAdminQuery);
    $countAdmin = $countAdminStatement->fetchColumn();

    // Conditions basées sur le rôle et l'existence de l'enseignant administrateur
    if ($role == 'enseignant' && $countAdmin == 0) {
        $administrateur = 1; // L'utilisateur est configuré en tant qu'administrateur
    } elseif ($role == 'etudiant') {
        // Vérification de l'existence d'au moins un enseignant
        $checkEnseignantQuery = "SELECT COUNT(*) FROM utilisateur WHERE role = 'enseignant'";
        $checkEnseignantStatement = $pdo->query($checkEnseignantQuery);
        $countEnseignant = $checkEnseignantStatement->fetchColumn();

        if ($countEnseignant == 0) {
            // Aucun enseignant n'est présent, affichage d'une erreur
            echo '<script>alert("Aucun enseignant existant."); window.location.replace("index.php");</script>';
            exit();
        }

        $administrateur = 0; // L'utilisateur n'est pas administrateur
    } else {
        $administrateur = 0; // L'utilisateur n'est pas administrateur
    }

    // Insertion des données dans la table utilisateur
    $insertUserQuery = "INSERT INTO utilisateur (matricule, username, password, role, administrateur) VALUES (:matricule, :username, :password, :role, :administrateur)";
    $insertUserStatement = $pdo->prepare($insertUserQuery);
    $insertUserStatement->bindParam(":matricule", $newMatricule, PDO::PARAM_INT);
    $insertUserStatement->bindParam(":username", $newUsername, PDO::PARAM_STR);
    $insertUserStatement->bindParam(":password", $hashedPassword, PDO::PARAM_STR);
    $insertUserStatement->bindParam(":role", $role, PDO::PARAM_STR);
    $insertUserStatement->bindParam(":administrateur", $administrateur, PDO::PARAM_INT);
    $insertUserStatement->execute();

    // Redirection vers le formulaire de connexion
    header("Location: index.php");
    exit();
}

// Fermeture de la connexion
$pdo = null;
?>
