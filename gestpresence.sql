-- Table pour stocker les classes
CREATE TABLE classe (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    PRIMARY KEY (id)
);

-- Table pour stocker les utilisateurs (étudiants, enseignants, administrateurs) liés à une classe
CREATE TABLE utilisateur (
    matricule INT NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('etudiant', 'enseignant') NOT NULL,
    administrateur BOOLEAN NOT NULL DEFAULT 0,
    classe_id INT,
    PRIMARY KEY (matricule),
    FOREIGN KEY (classe_id) REFERENCES classe (id)
);

-- Table pour gérer les sessions liées à une classe
CREATE TABLE session (
    id INT NOT NULL AUTO_INCREMENT,
    utilisateur_matricule INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expiration DATETIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule)
);

-- Création de la table cours liée à une classe
CREATE TABLE cours (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    heures_attribuees INT NOT NULL,
    classe_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (classe_id) REFERENCES classe (id)
);

-- Table pour gérer les plannings de cours liés à une classe
CREATE TABLE planning_cours (
    id INT NOT NULL AUTO_INCREMENT,
    cours_id INT NOT NULL,
    date DATE NOT NULL,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (cours_id) REFERENCES cours (id)
);

-- Création de la table profil liée à une classe
CREATE TABLE profil (
    id INT NOT NULL AUTO_INCREMENT,
    utilisateur_matricule INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    date_naissance DATE NOT NULL,
    classe_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
    FOREIGN KEY (classe_id) REFERENCES classe (id)
);

-- Table pour attribuer des étudiants et enseignants à des cours spécifiques
CREATE TABLE attribution_cours (
    id INT NOT NULL AUTO_INCREMENT,
    utilisateur_matricule INT NOT NULL,
    cours_id INT NOT NULL,
    classe_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
    FOREIGN KEY (cours_id) REFERENCES cours (id),
    FOREIGN KEY (classe_id) REFERENCES classe (id)
);

-- Table pour gérer les sessions académiques
CREATE TABLE session_academique (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    PRIMARY KEY (id)
);

-- Table pour enregistrer les présences et absences des étudiants
CREATE TABLE enregistrement_assiduite (
    id INT NOT NULL AUTO_INCREMENT,
    utilisateur_matricule INT NOT NULL,
    cours_id INT NOT NULL,
    session_academique_id INT NOT NULL,
    date DATE NOT NULL,
    present BOOLEAN NOT NULL,
    classe_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
    FOREIGN KEY (cours_id) REFERENCES cours (id),
    FOREIGN KEY (session_academique_id) REFERENCES session_academique (id),
    FOREIGN KEY (classe_id) REFERENCES classe (id)
);

-- Table pour gérer les justificatifs
CREATE TABLE justificatif (
    id INT NOT NULL AUTO_INCREMENT,
    utilisateur_matricule INT NOT NULL,
    enregistrement_assiduite_id INT NOT NULL,
    motif VARCHAR(255) NOT NULL,
    fichier VARCHAR(255),
    statut ENUM('en_attente', 'valide', 'rejete') NOT NULL,
    classe_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
    FOREIGN KEY (enregistrement_assiduite_id) REFERENCES enregistrement_assiduite (id),
    FOREIGN KEY (classe_id) REFERENCES classe (id)
);

-- Table pour gérer les notifications
CREATE TABLE notification (
    id INT NOT NULL AUTO_INCREMENT,
    utilisateur_matricule INT NOT NULL,
    message TEXT NOT NULL,
    lu BOOLEAN NOT NULL,
    classe_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
    FOREIGN KEY (classe_id) REFERENCES classe (id)
);

-- Table pour enregistrer l'historique des modifications
CREATE TABLE historique_modifications (
    id INT NOT NULL AUTO_INCREMENT,
    utilisateur_matricule INT NOT NULL,
    date_modification DATETIME NOT NULL,
    action_effectuee TEXT NOT NULL,
    classe_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
    FOREIGN KEY (classe_id) REFERENCES classe (id)
);

-- Table pour stocker des informations spécifiques aux étudiants
CREATE TABLE information_etudiant (
    utilisateur_matricule INT NOT NULL,
    classe_id INT,
    PRIMARY KEY (utilisateur_matricule),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
    FOREIGN KEY (classe_id) REFERENCES classe (id)
);

-- Table pour gérer les informations spécifiques aux enseignants
CREATE TABLE information_enseignant (
    utilisateur_matricule INT NOT NULL,
    specialite VARCHAR(255) NOT NULL,
    bureau VARCHAR(255),
    classe_id INT,
    PRIMARY KEY (utilisateur_matricule),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
    FOREIGN KEY (classe_id) REFERENCES classe (id)
);
