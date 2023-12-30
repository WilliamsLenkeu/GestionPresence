<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['matricule'])) {
    header('Location: index.php');
    exit;
}

// Connexion à la base de données (à adapter selon votre configuration)
include '../connexion.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupérer le matricule de l'utilisateur depuis la session
$matricule = $_SESSION['matricule'];

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $filiere_id = $_POST['filiere_id'];

    // Utilisation d'une transaction pour s'assurer que les deux requêtes réussissent ou échouent ensemble
    $conn->begin_transaction();

    // Insérer des données dans la table profil
    $sqlProfil = "INSERT INTO profil (utilisateur_matricule, nom, prenom, date_naissance) VALUES (?, ?, ?, ?)";
    $stmtProfil = $conn->prepare($sqlProfil);
    $stmtProfil->bind_param('ssss', $matricule, $nom, $prenom, $date_naissance);
    $profilSuccess = $stmtProfil->execute();

    // Insérer des données dans la table information_etudiant
    $sqlInfoEtudiant = "INSERT INTO information_etudiant (utilisateur_matricule, filiere_id) VALUES (?, ?)";
    $stmtInfoEtudiant = $conn->prepare($sqlInfoEtudiant);
    $stmtInfoEtudiant->bind_param('ss', $matricule, $filiere_id);
    $infoEtudiantSuccess = $stmtInfoEtudiant->execute();

    // Vérifier si les deux requêtes ont réussi
    if ($profilSuccess && $infoEtudiantSuccess) {
        // Valider les changements
        $conn->commit();

        // Rediriger vers le tableau de bord de l'étudiant
        header('Location: tableau_de_bord_etudiant.php');
        exit;
    } else {
        // Annuler les changements en cas d'échec
        $conn->rollback();
        echo 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.';
    }

    // Fermer les déclarations
    $stmtProfil->close();
    $stmtInfoEtudiant->close();
}

// Récupérer la liste des filières depuis la base de données
$sqlFilieres = "SELECT id, nom FROM filiere";
$resultFilieres = $conn->query($sqlFilieres);

// Fermer la connexion
$conn->close();
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Remplir Profil Étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css" type="text/css" />
    <link rel="icon" href="../image/logo.jpg" type="image/x-icon">
</head>

<body class="fw-bold">
    <div class="container-fluid dash">
        <!-- Ajoutez votre formulaire ici -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <!-- Insérez ici les champs du formulaire (nom, prénom, date de naissance, filière, etc.) -->
            <div class="mb-3">
                <label for="nom" class="form-label">Nom :</label>
                <input type="text" class="form-control" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom :</label>
                <input type="text" class="form-control" name="prenom" required>
            </div>
            <div class="mb-3">
                <label for="date_naissance" class="form-label">Date de Naissance :</label>
                <input type="date" class="form-control" name="date_naissance" required>
            </div>
            <div class="mb-3">
                <label for="filiere_id" class="form-label">Filière :</label>
                <select class="form-select" name="filiere_id" required>
                    <?php
                    // Afficher les options de filière
                    while ($rowFiliere = $resultFilieres->fetch_assoc()) {
                        echo '<option value="' . $rowFiliere['id'] . '">' . $rowFiliere['nom'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
