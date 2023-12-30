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
                <p class="card-text">Date de Naissance: <?php echo $dateNaissance; ?></p>
                <p class="card-text">Rôle: <?php echo $role; ?></p>

                <?php
                // Vérifier le rôle de l'utilisateur
                if ($role === 'enseignant') {
                    // Si l'utilisateur est un enseignant, afficher les informations de la table information_enseignant
                    $sql_enseignant = "SELECT specialite, bureau FROM information_enseignant WHERE utilisateur_matricule = ?";
                    $stmt_enseignant = $conn->prepare($sql_enseignant);
                    $stmt_enseignant->bind_param('s', $matricule);
                    $stmt_enseignant->execute();
                    $stmt_enseignant->store_result();

                    if ($stmt_enseignant->num_rows > 0) {
                        $stmt_enseignant->bind_result($specialite, $bureau);
                        $stmt_enseignant->fetch();
                ?>
                        <p class="card-text">Spécialité: <?php echo $specialite; ?></p>
                        <p class="card-text">Bureau: <?php echo $bureau; ?></p>
                <?php
                    }
                    $stmt_enseignant->close();
                } else {
                    // Si l'utilisateur est un étudiant, afficher les informations de la table information_etudiant
                    $sql_etudiant = "SELECT filiere_id FROM information_etudiant WHERE utilisateur_matricule = ?";
                    $stmt_etudiant = $conn->prepare($sql_etudiant);
                    $stmt_etudiant->bind_param('s', $matricule);
                    $stmt_etudiant->execute();
                    $stmt_etudiant->store_result();

                    if ($stmt_etudiant->num_rows > 0) {
                        $stmt_etudiant->bind_result($filiere_id);
                        $stmt_etudiant->fetch();

                        // Récupérer le nom de la filière
                        $sql_filiere = "SELECT nom FROM filiere WHERE id = ?";
                        $stmt_filiere = $conn->prepare($sql_filiere);
                        $stmt_filiere->bind_param('s', $filiere_id);
                        $stmt_filiere->execute();
                        $stmt_filiere->store_result();

                        if ($stmt_filiere->num_rows > 0) {
                            $stmt_filiere->bind_result($nom_filiere);
                            $stmt_filiere->fetch();
                ?>
                            <p class="card-text">Filière: <?php echo $nom_filiere; ?></p>
                <?php
                        }
                        $stmt_filiere->close();
                    }
                    $stmt_etudiant->close();
                }
                ?>
                <!-- Ajoutez d'autres informations de l'utilisateur ici -->
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <div class="container ">
                    <div class="row">
                        <!-- <div class="col-6">
                            <a href="#" class="btn btn-primary btn-sm w-100 shadow-sm border-1">Profil</a>
                        </div> -->
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
    header('Location: ./logout.php');
    exit;
}
?>
