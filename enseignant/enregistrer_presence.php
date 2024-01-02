<?php
// Démarrez la session pour accéder aux variables de session
session_start();

// Vérifiez si le matricule est présent dans la session
if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page logout.php du dossier parent
    header('Location: ../logout.php');
    exit;
}

// Inclure le fichier de connexion à la base de données
include '../connexion.php';

// Vérifier si des données de présence ont été soumises
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cours_id'])) {
    $coursId = $_POST['cours_id'];

    // Préparez la requête pour insérer les présences dans la base de données
    $sqlInsertPresence = "INSERT INTO presence (utilisateur_matricule, cours_id, date, present) VALUES (?, ?, NOW(), ?)";
    $stmtInsertPresence = $conn->prepare($sqlInsertPresence);

    // Parcourez les données de présence et exécutez la requête pour "Présent"
    if (isset($_POST['presence'])) {
        $presenceData = $_POST['presence'];
        foreach ($presenceData as $matricule => $present) {
            $matricule = intval($matricule);
            $present = intval($present);
            $stmtInsertPresence->bind_param('iii', $matricule, $coursId, $present);
            $stmtInsertPresence->execute();
        }
    }

    // Préparez la requête pour insérer les absences dans la base de données
    $sqlInsertAbsence = "INSERT INTO presence (utilisateur_matricule, cours_id, date, present) VALUES (?, ?, NOW(), 0)";
    $stmtInsertAbsence = $conn->prepare($sqlInsertAbsence);

    // Parcourez les données d'absence et exécutez la requête pour "Absent"
    if (isset($_POST['absence'])) {
        $absenceData = $_POST['absence'];
        foreach ($absenceData as $matricule => $absent) {
            $matricule = intval($matricule);
            $stmtInsertAbsence->bind_param('ii', $matricule, $coursId);
            $stmtInsertAbsence->execute();
        }
    }

    // Fermez les requêtes préparées
    $stmtInsertPresence->close();
    $stmtInsertAbsence->close();
}

// Fermez la connexion à la base de données
$conn->close();

// Redirigez l'utilisateur vers la page d'appel des étudiants
header('Location: dashboard_enseignant.php');
exit;
?>
