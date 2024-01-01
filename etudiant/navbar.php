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

// Récupérer le nom de la classe
$sqlNomClasse = "SELECT nom FROM classe WHERE id = ?";
$stmtNomClasse = $conn->prepare($sqlNomClasse);
$stmtNomClasse->bind_param("i", $classeId);
$stmtNomClasse->execute();
$stmtNomClasse->bind_result($nomClasse);
$stmtNomClasse->fetch();
$stmtNomClasse->close();

// Récupérer tous les ID des cours suivis par l'élève
$sqlListeCours = "SELECT cours.id
    FROM cours 
    JOIN attribution_cours ac ON ac.cours_id = cours.id
    WHERE ac.classe_id = ? AND ac.utilisateur_matricule = ? 
    ";
$stmtListeCours = $conn->prepare($sqlListeCours);
$stmtListeCours->bind_param("is", $classeId, $matricule);
$stmtListeCours->execute();
$resultListeCours = $stmtListeCours->get_result();
?>

<nav class="navbar navbar-expand-lg flex-column border-end border-secondary h-100">
    <div class="row page-title" style="width:100% ;background:#fff">
        <div class="row fs-2">Mes informations</div><br>
        <?php 
            echo '<table class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col">Nom du Cours</th>';
            echo '<th scope="col">Heures d\'absence</th>';
            echo '<th scope="col">Motif du Justificatif</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
        ?>
        <tbody>
            <?php
                $sommeAbsence = 0;
                // Calculer les heures d'absence pour chaque cours
                while ($row = $resultListeCours->fetch_assoc()) {

                    $sqlInfos = "SELECT c.nom, COALESCE(SUM(pc.heure_fin - pc.heure_debut), 0) as heure_absence, j.motif
                        FROM cours c
                        JOIN planning_cours pc ON c.id = pc.cours_id
                        JOIN enregistrement_assiduite ea ON ea.cours_id = c.id 
                        LEFT JOIN justificatif j ON j.enregistrement_assiduite_id = ea.id AND j.utilisateur_matricule = ea.utilisateur_matricule
                        WHERE ea.present = 0 AND c.id = ? AND ea.utilisateur_matricule = ?
                        ";

                    $stmtInfos = $conn->prepare($sqlInfos);
                    $stmtInfos->bind_param("is", $row['id'], $matricule);
                    $stmtInfos->execute();
                    $resultInfos = $stmtInfos->get_result();
                    $infoRow = $resultInfos->fetch_assoc();

                    if ($infoRow) {
                        // Afficher les informations dans une ligne de tableau
                        echo '<tr>';
                        echo '<td>' . $infoRow['nom'] . '</td>';
                        echo '<td>' . $infoRow['heure_absence'] . '</td>';
                        // Vérifier si le motif est vide
                        echo '<td>' . ($infoRow['motif'] ? $infoRow['motif'] : 'Aucun motif') . '</td>';
                        echo '</tr>';
                        $sommeAbsence += $infoRow['heure_absence'];
                    }
                }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total heures d'absence: </th>
                <th><?php echo $sommeAbsence ?> heure(s)</th>
            </tr>
        </tfoot>
    </table>
</nav>
