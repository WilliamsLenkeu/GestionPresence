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
$sqlListeCours = "SELECT c.id
    FROM attribution_cours ac
    JOIN utilisateur u ON ac.utilisateur_matricule = u.matricule
    JOIN cours c ON ac.cours_id = c.id
    WHERE u.matricule = ?";
$stmtListeCours = $conn->prepare($sqlListeCours);
$stmtListeCours->bind_param("i", $matricule);
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
                    $sqlInfos = "SELECT c.nom, COALESCE(SUM(TIME_TO_SEC(TIMEDIFF(pc.heure_fin, pc.heure_debut)) / 3600), 0) as heure_absence, p.justificatif
                        FROM cours c
                        JOIN planning_cours pc ON c.id = pc.cours_id
                        LEFT JOIN presence p ON p.cours_id = c.id AND p.utilisateur_matricule = ?
                        WHERE c.id = ? and p.present=0
                        GROUP BY c.id
                        ";

                    $stmtInfos = $conn->prepare($sqlInfos);
                    $stmtInfos->bind_param("ii", $matricule, $row['id']);
                    $stmtInfos->execute();
                    $resultInfos = $stmtInfos->get_result();
                    $infoRow = $resultInfos->fetch_assoc();

                    if ($infoRow) {
                        // Afficher les informations dans une ligne de tableau
                        echo '<tr>';
                        echo '<td>' . $infoRow['nom'] . '</td>';
                        echo '<td>' . $infoRow['heure_absence'] . '</td>';
                        // Vérifier si le motif est vide
                        echo '<td>' . ($infoRow['justificatif'] ? $infoRow['justificatif'] : 'Aucun motif') . '</td>';
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
