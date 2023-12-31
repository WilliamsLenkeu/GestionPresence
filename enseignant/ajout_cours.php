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
    $classeId = $_POST["classe_id"];
    $enseignants = isset($_POST["enseignants"]) ? $_POST["enseignants"] : [];

    // Ajout du champ facultatif pour les cours
    $facultatif = isset($_POST["facultatif"]) ? 1 : 0;

    // Insérer le cours dans la table "cours"
    $sqlInsertCours = "INSERT INTO cours (nom, description, facultatif, classe_id) VALUES (?, ?, ?, ?)";
    $stmtInsertCours = $conn->prepare($sqlInsertCours);
    $stmtInsertCours->bind_param("ssii", $nomCours, $descriptionCours, $facultatif, $classeId);
    $stmtInsertCours->execute();
    $coursId = $stmtInsertCours->insert_id;

    // Associer les enseignants au cours dans la table "attribution_cours"
    foreach ($enseignants as $enseignant) {
        $sqlInsertAttribution = "INSERT INTO attribution_cours (utilisateur_matricule, cours_id, classe_id) VALUES (?, ?, ?)";
        $stmtInsertAttribution = $conn->prepare($sqlInsertAttribution);
        $stmtInsertAttribution->bind_param("iii", $enseignant, $coursId, $classeId);
        $stmtInsertAttribution->execute();
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
                                <label for="classe_id" class="form-label">Classe</label>
                                <select class="form-control" id="classe_id" name="classe_id" required>
                                    <?php
                                    while ($rowClasse = $resultClasses->fetch_assoc()) {
                                        echo '<option value="' . $rowClasse["id"] . '">' . $rowClasse["nom"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="facultatif" name="facultatif">
                                <label class="form-check-label" for="facultatif">Cours facultatif</label>
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
