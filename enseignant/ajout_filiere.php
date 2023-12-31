<?php
// Démarrez la session pour accéder aux variables de session
session_start();

include '../connexion.php';

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données avec MySQLi a échoué : " . $conn->connect_error);
}

// Utilisation d'une requête préparée pour éviter les attaques par injection SQL
$sql = "SELECT administrateur FROM utilisateur WHERE matricule = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $_SESSION['matricule']);
$stmt->execute();
$stmt->store_result();

// Vérifier si l'utilisateur est administrateur
$isAdmin = false;
if ($stmt->num_rows > 0) {
    $stmt->bind_result($adminStatus);
    $stmt->fetch();
    $isAdmin = $adminStatus == 1;
}

// Vérifier si l'utilisateur est administrateur, sinon rediriger
if (!$isAdmin) {
    header('Location: index.php'); // Remplacez "index.php" par la page de redirection appropriée
    exit;
}

// Traitement du formulaire d'ajout de filière
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    // Utilisation d'une requête préparée pour éviter les attaques par injection SQL
    $sqlAjoutFiliere = "INSERT INTO filiere (nom, description) VALUES (?, ?)";
    $stmtAjoutFiliere = $conn->prepare($sqlAjoutFiliere);
    $stmtAjoutFiliere->bind_param('ss', $nom, $description);

    if ($stmtAjoutFiliere->execute()) {
        header('Location: liste_filiere.php'); // Rediriger après l'ajout de la filière
        exit;
    } else {
        echo 'Une erreur est survenue lors de l\'ajout de la filière. Veuillez réessayer.';
    }

    // Fermer la déclaration
    $stmtAjoutFiliere->close();
}

?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter une Filière</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css" type="text/css" />
    <link rel="icon" href="../image/logo.jpg" type="image/x-icon">
</head>

<body class="fw-bold">
    <div class="container-fluid dash">

        <!-- Section du tableau de bord -->
        <div class="row dash-1">
            <div class="col-md-2 border-end border-secondary text-center">
                <?php include './navbar.php'; ?>
            </div>
            <div class="col-md col-12">
                <div class="row page-title shadow-lg">
                    <div class="fs-2 mt-3"> Ajouter une Filière </div>
                </div>
                <div class="row mt-4 fw-normal">
                    <!-- Formulaire d'ajout de filière -->
                    <div class="col-md-6">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom de la Filière :</label>
                                <input type="text" class="form-control" name="nom" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description :</label>
                                <textarea class="form-control" name="description" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Colonne de droite pour les informations de l'utilisateur -->
            <div class="col-md-2 border-start border-secondary">
                <?php include '../templates/user-card.php'; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
