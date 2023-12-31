<?php
session_start();

if (!isset($_SESSION['matricule'])) {
    header('Location: ../logout.php');
    exit;
}

include '../connexion.php';

$matricule = $_SESSION['matricule'];

$sqlProfil = "SELECT classe_id FROM information_etudiant WHERE utilisateur_matricule = ?";
$stmtProfil = $conn->prepare($sqlProfil);
$stmtProfil->bind_param("i", $matricule);
$stmtProfil->execute();
$stmtProfil->store_result();

if ($stmtProfil->num_rows == 0) {
    // L'étudiant n'a pas de profil, rediriger vers la page de profil
    header('Location: remplir_profil_etudiant.php');
    exit;
}

$stmtProfil->bind_result($classeId);
$stmtProfil->fetch();
$stmtProfil->close();

// Récupérer la liste des étudiants de la même classe avec la somme de leurs heures d'absence
$sqlListeEtudiants = "
    SELECT u.matricule, p.nom, p.prenom, COALESCE(SUM(c.heure_fin - c.heure_debut), 0) AS total_heures_absence
    FROM utilisateur u
    JOIN profil p ON u.matricule = p.utilisateur_matricule
    LEFT JOIN enregistrement_assiduite ea ON u.matricule = ea.utilisateur_matricule
    LEFT JOIN planning_cours c ON ea.cours_id = c.cours_id
    LEFT JOIN information_etudiant ie ON u.matricule = ie.utilisateur_matricule
    WHERE u.role = 'etudiant' AND ie.classe_id = ?
    GROUP BY u.matricule, p.nom, p.prenom;";

$stmtListeEtudiants = $conn->prepare($sqlListeEtudiants);
$stmtListeEtudiants->bind_param("i", $classeId);
$stmtListeEtudiants->execute();
$resultListeEtudiants = $stmtListeEtudiants->get_result();

// Récupérer le nom de la classe
$sqlNomClasse = "SELECT nom FROM classe WHERE id = ?";
$stmtNomClasse = $conn->prepare($sqlNomClasse);
$stmtNomClasse->bind_param("i", $classeId);
$stmtNomClasse->execute();
$stmtNomClasse->bind_result($nomClasse);
$stmtNomClasse->fetch();
$stmtNomClasse->close();
?>
<nav class="navbar navbar-expand-lg flex-column border-end border-secondary h-100">
    <div class="row page-title" style="width:100% ;background:#fff">
        <div class="fs-2"> Liste des élèves de <?php echo $nomClasse ?></div>
    </div>
    <!-- Liste des étudiants avec la somme de leurs heures d'absence -->
    <?php
    if ($resultListeEtudiants->num_rows > 0) {
        while ($rowEtudiant = $resultListeEtudiants->fetch_assoc()) {
            echo '<br><div class="col-md-12 mb-4">';
            echo '<div class="card">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $rowEtudiant['nom'] . ' ' . $rowEtudiant['prenom'] . '</h5>';
            echo 'Matricule : ' . $rowEtudiant['matricule'] . ', ';
            echo $rowEtudiant['total_heures_absence'] . ' heure(s) d\'absence ';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="col-md-12">';
        echo '<p>Aucun étudiant trouvé dans la même classe.</p>';
        echo '</div>';
    }
    ?>
</nav>
