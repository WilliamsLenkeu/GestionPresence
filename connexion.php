<?php
    $servername = "sql105.infinityfree.com";
    $username = "if0_35744342";
    $password = "l1e2n3k4e5u6";
    $dbname = "if0_35744342_gestpresence";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données avec mysqli a échoué : " . $conn->connect_error);
    }

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Définir le mode d'erreur de PDO sur Exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connexion à la base de données réussie";
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
?>