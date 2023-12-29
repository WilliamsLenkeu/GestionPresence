<?php
// Connexion à la base de données (à adapter selon votre configuration)
include './connexion.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Démarrez la session pour accéder aux variables de session
session_start();

// Vérifiez si l'utilisateur est connecté en vérifiant la présence de son matricule dans la session
if (isset($_SESSION['matricule'])) {
    // Récupérez le matricule de la session
    $matricule = $_SESSION['matricule'];

    // Vérifiez si l'utilisateur a déjà un profil
    $checkProfilQuery = "SELECT id FROM profil WHERE utilisateur_matricule = ?";
    $stmtCheckProfil = $conn->prepare($checkProfilQuery);
    $stmtCheckProfil->bind_param('s', $matricule);
    $stmtCheckProfil->execute();
    $stmtCheckProfil->store_result();

    if ($stmtCheckProfil->num_rows > 0) {
        // L'utilisateur a déjà un profil, rediriger vers une page appropriée
        header('Location: page_deja_profil.php');
        exit;
    }

    // Si l'utilisateur n'a pas encore de profil, insérez les données du formulaire dans la table profil
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $dateNaissance = $_POST['date_naissance'];

        // Utilisation d'une requête préparée pour éviter les attaques par injection SQL
        $insertProfilQuery = "INSERT INTO profil (utilisateur_matricule, nom, prenom, date_naissance) VALUES (?, ?, ?, ?)";
        $stmtInsertProfil = $conn->prepare($insertProfilQuery);
        $stmtInsertProfil->bind_param('ssss', $matricule, $nom, $prenom, $dateNaissance);

        if ($stmtInsertProfil->execute()) {
            // Rediriger vers une page de succès
            // header('Location: page_profil_succes.php');
            // exit;
            session_start();
            $roleRec = $_SESSION['role'] ;

            // Rediriger vers le tableau de bord correspondant
            switch ($roleRec) {
                case 'etudiant':
                    header('Location: tableau_de_bord_etudiant.php');
                    exit;
                    break;

                case 'enseignant':
                    header('Location: enseignant/dashboard_enseignant.php');
                    exit;
                    break;

                // Ajoutez d'autres cas selon vos besoins (administrateur, etc.)
            }
        } else {
            // Une erreur s'est produite lors de l'insertion
            echo "Erreur lors de l'insertion du profil : " . $stmtInsertProfil->error;
        }
    } else {
        // Méthode de requête incorrecte, rediriger vers une page appropriée
        header('Location: page_erreur.php');
        exit;
    }

    // Fermer la connexion
    $stmtCheckProfil->close();
    $stmtInsertProfil->close();
    $conn->close();
} else {
    // Rediriger si le matricule n'est pas présent dans la session
    header('Location: page_non_connecte.php');
    exit;
}
?>
