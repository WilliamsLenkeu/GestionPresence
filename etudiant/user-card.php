<?php

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['matricule'])) {
    header('Location: ../index.php');
    exit;
}

// Connexion à la base de données (à adapter selon votre configuration)
include '../connexion.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupérer le matricule de l'utilisateur connecté
$matricule = $_SESSION['matricule'];

// Préparez la requête pour récupérer les informations de l'étudiant
$infoQuery = "SELECT ie.nom, ie.prenom, ie.date_naissance, ie.classe_id, c.nom AS nom_classe
              FROM information_etudiant ie
              LEFT JOIN classe c ON ie.classe_id = c.id
              WHERE ie.utilisateur_matricule = ?";

// Préparez et exécutez la requête
$infoStmt = $conn->prepare($infoQuery);
$infoStmt->bind_param('i', $matricule);
$infoStmt->execute();
$infoStmt->store_result();

// Vérifiez si le matricule a été trouvé dans la base de données
if ($infoStmt->num_rows > 0) {
    // Liaison des résultats de la requête aux variables
    $infoStmt->bind_result($nom, $prenom, $dateNaissance, $classeId, $nomClasse);
    $infoStmt->fetch();
} else {
    // Matricule non trouvé, afficher une alerte d'erreur avec Bootstrap
    echo '<script>alert("Matricule non trouvé dans la base de données."); window.location.replace("../index.php");</script>';
}

// Fermer la connexion
$infoStmt->close();
$conn->close();
?>

<!-- Le reste de votre code HTML peut maintenant utiliser les variables récupérées -->
<div class="card my-3 shadow-sm">
    <div class="card-header">
        <h5 class="card-title mb-0">Informations Utilisateur</h5>
    </div>
    <div class="card-body">
        <!-- Afficher les détails de l'utilisateur ici -->
        <p class="card-text">Matricule: <?php echo $matricule; ?></p>
        <p class="card-text">Nom: <?php echo htmlspecialchars($nom); ?></p>
        <p class="card-text">Prénom: <?php echo htmlspecialchars($prenom); ?></p>
        <p class="card-text">Date de Naissance: <?php echo $dateNaissance; ?></p>
        <p class="card-text">Rôle: <?php echo $_SESSION['role']; ?></p>
        <p class="card-text">Classe: <?php echo ($classeId) ? htmlspecialchars($nomClasse) : "Non assignée"; ?></p>
    </div>
    <div class="card-footer bg-transparent border-top-0">
        <div class="container ">
            <div class="row">
                <div class="col-3"></div>
                <div class="col-6">
                    <!-- Bouton de déconnexion -->
                    <a href="../logout.php" class="btn btn-danger btn-sm w-100 shadow- border-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                            <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                        </svg>
                    </a>
                </div>
                <div class="col-3"></div>
            </div>
        </div>
    </div>
</div>
