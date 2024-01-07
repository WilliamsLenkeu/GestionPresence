<?php
if (!isset($_SESSION['matricule'])) {
    // Redirige vers la page index.php du dossier parent
    header('Location: ../logout.php');
    exit;
}

// Connexion à la base de données (à adapter selon votre configuration)
include '../connexion.php';

$conn = new mysqli($servername, $username, $password, $dbname);

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

?>

<nav class="navbar navbar-expand-lg flex-column border-bottom border-secondary h-100">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav pt-1 flex-column me-auto mb-2 mb-lg-0 w-100">
                <liv class="nav-item mt-2 mb-3">
                    <a class="navbar-brand" href="../enseignant/dashboard_enseignant.php">
                        <img src="../image/logo-banner.jpg" alt="" class="img-fluid">
                    </a>
                </liv>
                <li class="nav-item btn btn-outline-secondary my-2 shadow-sm">
                    <a class="nav-link" aria-current="page" href="../enseignant/dashboard_enseignant.php">
                      <svg class="mb-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-speedometer2" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
                            <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10m8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <?php if ($isAdmin == 1) : ?>
                    <!-- Ajoutez ce bouton uniquement si l'utilisateur est un administrateur -->
                    <li class="nav-item btn btn-outline-secondary my-2 shadow-sm">
                        <a class="nav-link" href="./gestion_utilsateur.php">
                            <svg class="mb-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                <path d="M3.13 9a6.002 6.002 0 0 1 10.742 0h1.414a8 8 0 0 0-12.156 0H3.129zM8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                                <path d="M8 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/>
                            </svg>
                            Utilisateur
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item btn btn-outline-secondary my-2 shadow-sm">
                    <a class="nav-link" href="./planning.php">
                    <svg class="mb-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                    </svg>
                        Planning
                    </a>
                </li><li class="nav-item btn btn-outline-secondary my-2 shadow-sm">
                    <a class="nav-link" href="./liste_filiere.php">
                      <svg class="mb-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-journal-richtext" viewBox="0 0 16 16">
                            <path d="M7.5 3.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0m-.861 1.542 1.33.886 1.854-1.855a.25.25 0 0 1 .289-.047L11 4.75V7a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 7v-.5s1.54-1.274 1.639-1.208M5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5"/>
                            <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                            <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                        </svg>
                        Classe
                    </a>
                </li>
                <li class="nav-item btn btn-outline-secondary my-2 shadow-sm">
                    <a class="nav-link" href="./appel.php">
                        <svg class="mb-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                            <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                            <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
                        </svg>
                        Liste d'Appels
                    </a>
                </li>
                <li class="nav-item btn btn-outline-secondary my-2 shadow-sm">
                    <a class="nav-link" href="./liste_etudiant.php">
                        <svg class="mb-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-badge-fill" viewBox="0 0 16 16">
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm4.5 0a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6m5 2.755C12.146 12.825 10.623 12 8 12s-4.146.826-5 1.755V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1z"/>
                        </svg>
                        Etudiants
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>