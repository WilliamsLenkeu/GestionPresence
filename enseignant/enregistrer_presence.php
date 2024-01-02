<?php
session_start();

if (!isset($_SESSION['matricule'])) {
    header('Location: ../logout.php');
    exit;
}

include '../connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si des données de présence ont été soumises
    if (isset($_POST['presence']) && is_array($_POST['presence'])) {
        $matriculeList = array_keys($_POST['presence']);

        // Récupérer l'ID du cours
        $sqlCoursId = "SELECT id FROM cours WHERE nom = ?";
        $stmtCoursId = $conn->prepare($sqlCoursId);
        $stmtCoursId->bind_param('s', $_GET['cours']);
        $stmtCoursId->execute();
        $resultCoursId = $stmtCoursId->get_result();

        if ($resultCoursId->num_rows > 0) {
            // L'ID du cours existe
            $coursId = $resultCoursId->fetch_assoc()['id'];

            // Insérer les données de présence dans la table "presence"
            $sqlInsertPresence = "INSERT INTO presence (utilisateur_matricule, cours_id, date, present, justificatif) VALUES (?, ?, CURRENT_DATE(), 1, ?) ON DUPLICATE KEY UPDATE present = 1, justificatif = ?";
            $stmtInsertPresence = $conn->prepare($sqlInsertPresence);

            foreach ($matriculeList as $matricule) {
                // Vous devez définir le justificatif ici (vrai ou faux)
                $justificatif = isset($_POST['presence'][$matricule]) ? 1 : 0;

                $stmtInsertPresence->bind_param('iiss', $matricule, $coursId, $justificatif, $justificatif);
                $stmtInsertPresence->execute();
            }

            $stmtInsertPresence->close();
            $conn->close();

            header('Location: dashboard_enseignant.php');
            exit;
        } else {
            // L'ID du cours n'a pas été trouvé
            header('Location: dashboard_enseignant.php?error=cours_not_found');
            exit;
        }
    } else {
        // Aucune donnée de présence soumise
        header('Location: dashboard_enseignant.php');
        exit;
    }
} else {
    // Rediriger si la méthode HTTP n'est pas POST
    header('Location: dashboard_enseignant.php');
    exit;
}
?>
