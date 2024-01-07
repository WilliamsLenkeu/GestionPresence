<?php
// Démarrez la session pour accéder aux variables de session
session_start();

// Vérifiez si le matricule est présent dans la session
if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page index.php du dossier parent
    header('Location: ../logout.php');
    exit;
}

// Vérifiez si l'utilisateur a le droit d'administrateur
if ($_SESSION['isAdmin'] != 1) {
    // Redirige vers la page d'accueil
    header('Location: ../index.php');
    exit;
}

// Vérifiez si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inclure le fichier de connexion à la base de données
    include '../connexion.php';

    // Récupérer le matricule de l'utilisateur depuis le formulaire
    $matricule = $_POST['matricule'];

    // Récupérer la nouvelle valeur du statut administrateur depuis le formulaire
    $isAdmin = isset($_POST['isAdmin']) ? 1 : 0;

    // Mettre à jour le statut administrateur dans la base de données
    $sql = "UPDATE utilisateur SET administrateur = ? WHERE matricule = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $isAdmin, $matricule);
    $stmt->execute();

    // Rediriger vers la page de gestion des utilisateurs
    header('Location: gestion_utilisateur.php');
    exit;
} else {
    // Rediriger vers la page de gestion des utilisateurs si le formulaire n'a pas été soumis
    header('Location: gestion_utilisateur.php');
    exit;
}
?>
