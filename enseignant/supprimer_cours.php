<?php
session_start();
include '../connexion.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['matricule'])) {
    header('Location: ../logout.php');
    exit;
}

// Vérifier si l'utilisateur est administrateur
$sqlAdmin = "SELECT administrateur FROM utilisateur WHERE matricule = ?";
$stmtAdmin = $conn->prepare($sqlAdmin);
$stmtAdmin->bind_param('s', $_SESSION['matricule']);
$stmtAdmin->execute();
$stmtAdmin->store_result();

$isAdmin = false;
if ($stmtAdmin->num_rows > 0) {
    $stmtAdmin->bind_result($adminStatus);
    $stmtAdmin->fetch();
    $isAdmin = $adminStatus == 1;
}

$stmtAdmin->close();

// Vérifier si l'ID du cours est défini dans l'URL
if (isset($_GET['id'])) {
    $coursId = $_GET['id'];

    // Supprimer tous les éléments liés au cours
    if ($isAdmin) {
        // Supprimer les enregistrements dans la table attribution_cours
        $sqlSupprimerAttribution = "DELETE FROM attribution_cours WHERE cours_id = ?";
        $stmtSupprimerAttribution = $conn->prepare($sqlSupprimerAttribution);
        $stmtSupprimerAttribution->bind_param('i', $coursId);
        $stmtSupprimerAttribution->execute();
        $stmtSupprimerAttribution->close();

        // Supprimer les plannings de cours liés
        $sqlSupprimerPlanning = "DELETE FROM planning_cours WHERE cours_id = ?";
        $stmtSupprimerPlanning = $conn->prepare($sqlSupprimerPlanning);
        $stmtSupprimerPlanning->bind_param('i', $coursId);
        $stmtSupprimerPlanning->execute();
        $stmtSupprimerPlanning->close();

        // Supprimer les enregistrements d'assiduité liés
        $sqlSupprimerAssiduite = "DELETE FROM enregistrement_assiduite WHERE cours_id = ?";
        $stmtSupprimerAssiduite = $conn->prepare($sqlSupprimerAssiduite);
        $stmtSupprimerAssiduite->bind_param('i', $coursId);
        $stmtSupprimerAssiduite->execute();
        $stmtSupprimerAssiduite->close();

        // Supprimer le cours lui-même
        $sqlSupprimerCours = "DELETE FROM cours WHERE id = ?";
        $stmtSupprimerCours = $conn->prepare($sqlSupprimerCours);
        $stmtSupprimerCours->bind_param('i', $coursId);
        $stmtSupprimerCours->execute();
        $stmtSupprimerCours->close();

        // Rediriger vers la liste des cours après la suppression
        header('Location: liste_cours.php');
        exit;
    } else {
        // Rediriger vers la liste des cours avec un message d'erreur si l'utilisateur n'est pas administrateur
        header('Location: liste_cours.php?error=1');
        exit;
    }
} else {
    // Rediriger vers la liste des cours si l'ID du cours n'est pas défini
    header('Location: liste_cours.php');
    exit;
}
?>
