<?php
session_start();

include '../connexion.php';

if (!isset($_SESSION['matricule'])) {
    header('Location: ../logout.php');
    exit;
}

if ($conn->connect_error) {
    die("La connexion à la base de données avec MySQLi a échoué : " . $conn->connect_error);
}

// Vérifier si l'utilisateur est administrateur
$sqlAdmin = "SELECT administrateur FROM utilisateur WHERE matricule = ?";
$stmtAdmin = $conn->prepare($sqlAdmin);
$stmtAdmin->bind_param('i', $_SESSION['matricule']);
$stmtAdmin->execute();
$stmtAdmin->bind_result($isAdmin);
$stmtAdmin->fetch();
$stmtAdmin->close();

// Vérifier si le matricule de l'étudiant est défini dans l'URL
if (isset($_GET['matricule'])) {
    $matriculeEtudiant = $_GET['matricule'];

    // Supprimer les enregistrements dans la table presence liés à l'étudiant
    $sqlSupprimerPresence = "DELETE FROM presence WHERE utilisateur_matricule = ?";
    $stmtSupprimerPresence = $conn->prepare($sqlSupprimerPresence);
    $stmtSupprimerPresence->bind_param('i', $matriculeEtudiant);
    $stmtSupprimerPresence->execute();
    $stmtSupprimerPresence->close();

    // Supprimer les informations personnelles de l'étudiant
    $sqlSupprimerInfoEtudiant = "DELETE FROM information_etudiant WHERE utilisateur_matricule = ?";
    $stmtSupprimerInfoEtudiant = $conn->prepare($sqlSupprimerInfoEtudiant);
    $stmtSupprimerInfoEtudiant->bind_param('i', $matriculeEtudiant);
    $stmtSupprimerInfoEtudiant->execute();
    $stmtSupprimerInfoEtudiant->close();

    // Supprimer l'étudiant lui-même
    $sqlSupprimerEtudiant = "DELETE FROM utilisateur WHERE matricule = ?";
    $stmtSupprimerEtudiant = $conn->prepare($sqlSupprimerEtudiant);
    $stmtSupprimerEtudiant->bind_param('i', $matriculeEtudiant);
    $stmtSupprimerEtudiant->execute();
    $stmtSupprimerEtudiant->close();

    // Rediriger vers la liste des étudiants après la suppression
    header('Location: liste_etudiant.php');
    exit;
} else {
    // Rediriger vers la liste des étudiants si le matricule de l'étudiant n'est pas défini
    header('Location: liste_etudiant.php');
    exit;
}
?>
