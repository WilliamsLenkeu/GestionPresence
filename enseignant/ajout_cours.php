<?php
session_start();
include '../connexion.php';

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données avec MySQLi a échoué : " . $conn->connect_error);
}

// Récupérer la liste des enseignants
$sqlEnseignants = "SELECT u.matricule, u.username, p.prenom, p.nom FROM utilisateur u INNER JOIN profil p ON u.matricule = p.utilisateur_matricule WHERE u.role = 'enseignant'";
$resultEnseignants = $conn->query($sqlEnseignants);

// Récupérer la liste des élèves avec leurs informations de profil
$sqlEleves = "SELECT u.matricule, u.username, p.prenom, p.nom FROM utilisateur u INNER JOIN profil p ON u.matricule = p.utilisateur_matricule WHERE u.role = 'etudiant'";
$resultEleves = $conn->query($sqlEleves);

// Récupérer la liste des classes
$sqlClasses = "SELECT id, nom FROM classe";
$resultClasses = $conn->query($sqlClasses);

// Gérer la soumission du formulaire d'ajout de cours
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nomCours = $_POST["nom_cours"];
    $descriptionCours = $_POST["description_cours"];
    $heuresAttribuees = $_POST["heures_attribuees"];
    $classeId = $_POST["classe_id"];
    $heureDebut = $_POST["heure_debut"];
    $heureFin = $_POST["heure_fin"];
    $enseignants = isset($_POST["enseignants"]) ? $_POST["enseignants"] : [];
    $eleves = isset($_POST["eleves"]) ? $_POST["eleves"] : [];

    // Vérifier la disponibilité de la plage horaire dans le planning
    $sqlCheckPlanning = "SELECT COUNT(*) FROM planning_cours WHERE classe_id = ? AND date = ? AND ((heure_debut <= ? AND heure_fin >= ?) OR (heure_debut <= ? AND heure_fin >= ?))";
    $stmtCheckPlanning = $conn->prepare($sqlCheckPlanning);
    $stmtCheckPlanning->bind_param("isssss", $classeId, $dateCours, $heureDebut, $heureDebut, $heureFin, $heureFin);

    // Paramètre de date du cours
    $dateCours = date('d-m-Y');  // Vous pouvez ajuster cela en fonction de votre application

    // Exécuter la requête
    $stmtCheckPlanning->execute();
    $stmtCheckPlanning->bind_result($planningCount);
    $stmtCheckPlanning->fetch();
    $stmtCheckPlanning->close();

    // Si la plage horaire n'est pas disponible, rediriger avec un message d'erreur
    if ($planningCount > 0) {
        header("Location: ajout_cours.php?error=planning_conflict");
        exit;
    }

    // Insérer le cours dans la table "cours"
    $sqlInsertCours = "INSERT INTO cours (nom, description, heures_attribuees, classe_id) VALUES (?, ?, ?, ?)";
    $stmtInsertCours = $conn->prepare($sqlInsertCours);
    $stmtInsertCours->bind_param("ssii", $nomCours, $descriptionCours, $heuresAttribuees, $classeId);
    $stmtInsertCours->execute();
    $coursId = $stmtInsertCours->insert_id;

    // Associer les enseignants au cours dans la table "attribution_cours"
    foreach ($enseignants as $enseignant) {
        $sqlInsertAttribution = "INSERT INTO attribution_cours (utilisateur_matricule, cours_id, classe_id) VALUES (?, ?, ?)";
        $stmtInsertAttribution = $conn->prepare($sqlInsertAttribution);
        $stmtInsertAttribution->bind_param("iii", $enseignant, $coursId, $classeId);
        $stmtInsertAttribution->execute();
    }

    // Associer les élèves au cours dans la table "attribution_eleves_cours"
    foreach ($eleves as $eleve) {
        $sqlInsertAttributionEleve = "INSERT INTO attribution_cours (utilisateur_matricule, cours_id, classe_id) VALUES (?, ?, ?)";
        $stmtInsertAttributionEleve = $conn->prepare($sqlInsertAttributionEleve);
        $stmtInsertAttributionEleve->bind_param("iii", $eleve, $coursId, $classeId);
        $stmtInsertAttributionEleve->execute();
    }

    // Rediriger vers la page de liste des cours après l'ajout
    header("Location: liste_cours.php");
    exit;
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css" type="text/css" />
    <link rel="icon" href="../image/logo.jpg" type="image/x-icon">
</head>

<body class="fw-bold">
    <div class="container-fluid dash">
        <div class="row dash-1">
            <div class="col-md-2 border-end border-secondary text-center">
                <?php include './navbar.php'; ?>
            </div>
            <div class="col-md col-12">
                <div class="row page-title shadow-lg">
                    <div class="fs-2 mt-3"> Ajouter un Cours </div>
                </div>
                <div class="row mt-4 fw-normal">
                    <div class="col-md-6">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="mb-3">
                                <label for="nom_cours" class="form-label">Nom du Cours</label>
                                <input type="text" class="form-control" id="nom_cours" name="nom_cours" required>
                            </div>
                            <div class="mb-3">
                                <label for="description_cours" class="form-label">Description</label>
                                <textarea class="form-control" id="description_cours" name="description_cours" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="heures_attribuees" class="form-label">Heures attribuées</label>
                                <input type="number" class="form-control" id="heures_attribuees" name="heures_attribuees" required>
                            </div>
                            <div class="mb-3">
                                <label for="heure_debut" class="form-label">Heure de début</label>
                                <input type="time" class="form-control" id="heure_debut" name="heure_debut" required>
                            </div>
                            <div class="mb-3">
                                <label for="heure_fin" class="form-label">Heure de fin</label>
                                <input type="time" class="form-control" id="heure_fin" name="heure_fin" required>
                            </div>
                            <div class="mb-3">
                                <label for="classe_id" class="form-label">Classe</label>
                                <select class="form-control" id="classe_id" name="classe_id" required>
                                    <?php
                                    while ($rowClasse = $resultClasses->fetch_assoc()) {
                                        echo '<option value="' . $rowClasse["id"] . '">' . $rowClasse["nom"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="enseignants" class="form-label">Enseignants</label>
                                <select multiple class="form-control" id="enseignants" name="enseignants[]">
                                    <?php
                                    while ($rowEnseignant = $resultEnseignants->fetch_assoc()) {
                                        echo '<option value="' . $rowEnseignant["matricule"] . '">' . $rowEnseignant["prenom"] . ' ' . $rowEnseignant["nom"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="eleves" class="form-label">Étudiants</label>
                                <select multiple class="form-control" id="eleves" name="eleves[]">
                                    <?php
                                    // Récupérer la liste des élèves
                                    while ($rowEleve = $resultEleves->fetch_assoc()) {
                                        $selected = in_array($rowEleve["matricule"], $eleves) ? 'selected' : '';
                                        echo '<option value="' . $rowEleve["matricule"] . '" ' . $selected . '>' . $rowEleve["nom"] . ' ' . $rowEleve["prenom"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-dark">Ajouter</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2 border-start border-secondary">
                <?php include '../templates/user-card.php'; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"/>
</body>

</html>
