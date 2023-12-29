<?php

// Vérifiez si l'utilisateur est connecté en vérifiant la présence de son matricule dans la session
if (isset($_SESSION['matricule'])) {
    // Récupérez le matricule de la session
    $matricule = $_SESSION['matricule'];

    // Connexion à la base de données (à adapter selon votre configuration)
    include '../connexion.php';

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    // Utilisation d'une requête préparée pour éviter les attaques par injection SQL
    $sql = "SELECT u.matricule, u.username, p.nom, p.prenom, p.date_naissance, u.role
            FROM utilisateur u
            JOIN profil p ON u.matricule = p.utilisateur_matricule
            WHERE u.matricule = ? LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $matricule);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($storedMatricule, $storedUsername, $nom, $prenom, $dateNaissance, $role);
        $stmt->fetch();
?>
        <div class="card my-3 shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations Utilisateur</h5>
            </div>
            <div class="card-body">
                <!-- Afficher les détails de l'utilisateur ici -->
                <h6 class="card-subtitle mb-2 text-muted">Nom Utilisateur: <?php echo $storedUsername; ?></h6>
                <p class="card-text">Matricule: <?php echo $storedMatricule; ?></p>
                <p class="card-text">Nom: <?php echo $nom; ?></p>
                <p class="card-text">Prénom: <?php echo $prenom; ?></p>
                <!-- <p class="card-text">Date de Naissance: <?php echo $dateNaissance; ?></p> -->
                <p class="card-text">Rôle: <?php echo $role; ?></p>
                <!-- Ajoutez d'autres informations de l'utilisateur ici -->
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <!-- Bouton pour accéder au profil -->
                <a href="#" class="btn btn-light btn-sm">Voir Profil</a>
            </div>
        </div>
<?php
    } else {
        // Matricule non trouvé, afficher une alerte d'erreur avec Bootstrap
        echo '<script>alert("Matricule non trouvé dans la base de données."); window.location.replace("index.php");</script>';
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
} else {
    // Rediriger si le matricule n'est pas présent dans la session
    header('Location: index.php');
    exit;
}
?>
