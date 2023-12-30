<?php
// Initialiser la session
session_start();

// Détruire toutes les variables de session
session_unset();
session_destroy();

// Rediriger vers la page d'accueil
header('Location: ./index.php');
exit;
?>
