<?php
// Démarrez la session pour accéder aux variables de session
session_start();

// Vérifiez si le matricule est présent dans la session
if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page index.php du dossier parent
    header('Location: ../logout.php');
    exit;
}

include '../connexion.php';

$matricule = $_SESSION['matricule'];

$sqlProfil = "SELECT filiere_id FROM information_etudiant WHERE utilisateur_matricule = ?";
$stmtProfil = $conn->prepare($sqlProfil);
$stmtProfil->bind_param("i", $matricule);
$stmtProfil->execute();
$stmtProfil->store_result();

if ($stmtProfil->num_rows == 0) {
    // L'étudiant n'a pas de profil, rediriger vers la page de profil
    header('Location: remplir_profil_etudiant.php');
    exit;
}

$stmtProfil->bind_result($filiereId);
$stmtProfil->fetch();
$stmtProfil->close();

// Récupérer la liste des étudiants de la même filière avec la somme de leurs heures d'absence
$sqlListeEtudiants = "SELECT u.matricule, p.nom, p.prenom, COALESCE(SUM(c.heures_attribuees), 0) AS total_heures_absence
FROM utilisateur u
JOIN profil p ON u.matricule = p.utilisateur_matricule
LEFT JOIN enregistrement_assiduite ea ON u.matricule = ea.utilisateur_matricule
LEFT JOIN cours c ON ea.cours_id = c.id
LEFT JOIN information_etudiant ie ON u.matricule = ie.utilisateur_matricule
WHERE u.role = 'etudiant' AND ie.filiere_id = ?
GROUP BY u.matricule, p.nom, p.prenom, c.nom;";

$stmtListeEtudiants = $conn->prepare($sqlListeEtudiants);
$stmtListeEtudiants->bind_param("i", $filiereId);
$stmtListeEtudiants->execute();
$resultListeEtudiants = $stmtListeEtudiants->get_result();

// Récupérer le nom de la filière
$sqlNomFiliere = "SELECT nom FROM filiere WHERE id = ?";
$stmtNomFiliere = $conn->prepare($sqlNomFiliere);
$stmtNomFiliere->bind_param("i", $filiereId);
$stmtNomFiliere->execute();
$stmtNomFiliere->bind_result($nomFiliere);
$stmtNomFiliere->fetch();
$stmtNomFiliere->close();
?>
<nav class="navbar navbar-expand-lg flex-column border-end border-secondary h-100">
    <div class="row page-title " style="width:100% ;background:#fff">
        <div class="fs-2 "> Liste des élèves de <?php echo $nomFiliere?></div>
    </div>
    <!-- Liste des étudiants avec la somme de leurs heures d'absence -->
    <?php
    if ($resultListeEtudiants->num_rows > 0) {
        while ($rowEtudiant = $resultListeEtudiants->fetch_assoc()) {
            echo '</br><div class="col-md-12 mb-4">';
            echo '<div class="card">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $rowEtudiant['nom'] . ' ' . $rowEtudiant['prenom'] . '</h5>';
            echo 'Matricule : ' . $rowEtudiant['matricule'] . ', ';
            echo  $rowEtudiant['total_heures_absence'] . 'heure(s) d\'absence ';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="col-md-12">';
        echo '<p>Aucun étudiant trouvé dans la même filière.</p>';
        echo '</div>';
    }
    ?>
</nav>
