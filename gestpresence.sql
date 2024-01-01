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

-- Table pour stocker les cours liés à une classe
CREATE TABLE cours (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    facultatif BOOLEAN NOT NULL,
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

-- Table pour attribuer des étudiants et enseignants à des cours spécifiques
CREATE TABLE attribution_cours (
    id INT NOT NULL AUTO_INCREMENT,
    utilisateur_matricule INT NOT NULL,
    cours_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
    FOREIGN KEY (cours_id) REFERENCES cours (id)
);

-- Table pour enregistrer les présences et absences des étudiants
CREATE TABLE presence (
    id INT NOT NULL AUTO_INCREMENT,
    utilisateur_matricule INT NOT NULL,
    cours_id INT NOT NULL,
    date DATE NOT NULL,
    present BOOLEAN NOT NULL,
    justificatif VARCHAR(255) NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
    FOREIGN KEY (cours_id) REFERENCES cours (id)
);

-- Table pour stocker des informations spécifiques aux étudiants
CREATE TABLE information_etudiant (
    utilisateur_matricule INT NOT NULL,
    classe_id INT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    date_naissance DATE NOT NULL,
    PRIMARY KEY (utilisateur_matricule),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
    FOREIGN KEY (classe_id) REFERENCES classe (id)
);

-- Table pour gérer les informations spécifiques aux enseignants
CREATE TABLE information_enseignant (
    utilisateur_matricule INT NOT NULL,
    specialite VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    date_naissance DATE NOT NULL,
    bureau VARCHAR(255),
    cours_id INT DEFAULT NULL,
    PRIMARY KEY (utilisateur_matricule),
    FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
    FOREIGN KEY (cours_id) REFERENCES cours (id)
);
