<?php
// Démarrez la session pour accéder aux variables de session
session_start();

include '../connexion.php';

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données avec MySQLi a échoué : " . $conn->connect_error);
}

// Utilisation d'une requête préparée pour éviter les attaques par injection SQL
$sqlAdmin = "SELECT administrateur FROM utilisateur WHERE matricule = ?";
$stmtAdmin = $conn->prepare($sqlAdmin);
$stmtAdmin->bind_param('s', $_SESSION['matricule']);
$stmtAdmin->execute();
$stmtAdmin->store_result();

// Vérifier si l'utilisateur est administrateur
$isAdmin = false;
if ($stmtAdmin->num_rows > 0) {
    $stmtAdmin->bind_result($adminStatus);
    $stmtAdmin->fetch();
    $isAdmin = $adminStatus == 1;
}

// Vérifier si l'ID de la classe est défini dans l'URL
if (isset($_GET['id'])) {
    $classeId = $_GET['id'];

    // Supprimer tous les éléments liés à la classe
    if ($isAdmin) {
        // Supprimer les enregistrements dans la table attribution_cours liés à la classe
        $sqlSupprimerAttribution = "DELETE FROM attribution_cours WHERE cours_id IN (SELECT id FROM cours WHERE classe_id = ?)";
        $stmtSupprimerAttribution = $conn->prepare($sqlSupprimerAttribution);
        $stmtSupprimerAttribution->bind_param('i', $classeId);
        $stmtSupprimerAttribution->execute();
        $stmtSupprimerAttribution->close();

        // Supprimer les plannings de cours liés à la classe
        $sqlSupprimerPlanning = "DELETE FROM planning_cours_jour WHERE cours_id IN (SELECT id FROM cours WHERE classe_id = ?)";
        $stmtSupprimerPlanning = $conn->prepare($sqlSupprimerPlanning);
        $stmtSupprimerPlanning->bind_param('i', $classeId);
        $stmtSupprimerPlanning->execute();
        $stmtSupprimerPlanning->close();

        // Supprimer les enregistrements de présence liés à la classe
        $sqlSupprimerPresence = "DELETE FROM presence WHERE cours_id IN (SELECT id FROM cours WHERE classe_id = ?)";
        $stmtSupprimerPresence = $conn->prepare($sqlSupprimerPresence);
        $stmtSupprimerPresence->bind_param('i', $classeId);
        $stmtSupprimerPresence->execute();
        $stmtSupprimerPresence->close();

        // Supprimer les cours liés à la classe
        $sqlSupprimerCours = "DELETE FROM cours WHERE classe_id = ?";
        $stmtSupprimerCours = $conn->prepare($sqlSupprimerCours);
        $stmtSupprimerCours->bind_param('i', $classeId);
        $stmtSupprimerCours->execute();
        $stmtSupprimerCours->close();

        // Supprimer la classe elle-même
        $sqlSupprimerClasse = "DELETE FROM classe WHERE id = ?";
        $stmtSupprimerClasse = $conn->prepare($sqlSupprimerClasse);
        $stmtSupprimerClasse->bind_param('i', $classeId);
        $stmtSupprimerClasse->execute();
        $stmtSupprimerClasse->close();

        // Rediriger vers la liste des classes après la suppression
        header('Location: liste_filiere.php');
        exit;
    } else {
        // Rediriger vers la liste des classes avec un message d'erreur si l'utilisateur n'est pas administrateur
        header('Location: liste_filiere.php?error=1');
        exit;
    }
} else {
    // Rediriger vers la liste des classes si l'ID de la classe n'est pas défini
    header('Location: liste_filiere.php');
    exit;
}
?>
