-- Table pour stocker les utilisateurs (étudiants, enseignants, administrateurs)
CREATE TABLE utilisateur ( 
	matricule INT NOT NULL, 
	username VARCHAR(255) NOT NULL, 
	password VARCHAR(255) NOT NULL, 
	role ENUM('etudiant', 'enseignant') NOT NULL, 
	administrateur BOOLEAN NOT NULL DEFAULT 0, 
	PRIMARY KEY (matricule) 
);

-- Table pour gérer les sessions
CREATE TABLE session (
  id INT NOT NULL AUTO_INCREMENT,
  utilisateur_matricule INT NOT NULL, -- Modification : Utilisation du matricule comme clé étrangère
  token VARCHAR(255) NOT NULL,
  expiration DATETIME NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule)
);

-- Table pour gérer les profils des utilisateurs
CREATE TABLE profil (
  id INT NOT NULL AUTO_INCREMENT,
  utilisateur_matricule INT NOT NULL, -- Modification : Utilisation du matricule comme clé étrangère
  nom VARCHAR(255) NOT NULL,
  prenom VARCHAR(255) NOT NULL,
  date_naissance DATE NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule)
);

CREATE TABLE cours ( 
	id INT NOT NULL AUTO_INCREMENT, 
	nom VARCHAR(255) NOT NULL, 
	description TEXT, 
	heures_attribuees INT NOT NULL, 
	PRIMARY KEY (id) 
);

-- Table pour attribuer des étudiants et enseignants à des cours spécifiques
CREATE TABLE attribution_cours (
  id INT NOT NULL AUTO_INCREMENT,
  utilisateur_matricule INT NOT NULL, -- Modification : Utilisation du matricule comme clé étrangère
  cours_id INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
  FOREIGN KEY (cours_id) REFERENCES cours (id)
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
  utilisateur_matricule INT NOT NULL, -- Modification : Utilisation du matricule comme clé étrangère
  cours_id INT NOT NULL,
  session_academique_id INT NOT NULL,
  date DATE NOT NULL,
  present BOOLEAN NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
  FOREIGN KEY (cours_id) REFERENCES cours (id),
  FOREIGN KEY (session_academique_id) REFERENCES session_academique (id)
);

-- Table pour gérer les justificatifs
CREATE TABLE justificatif (
  id INT NOT NULL AUTO_INCREMENT,
  utilisateur_matricule INT NOT NULL, -- Modification : Utilisation du matricule comme clé étrangère
  enregistrement_assiduite_id INT NOT NULL,
  motif VARCHAR(255) NOT NULL,
  fichier VARCHAR(255),
  statut ENUM('en_attente', 'valide', 'rejete') NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
  FOREIGN KEY (enregistrement_assiduite_id) REFERENCES enregistrement_assiduite (id)
);

-- Table pour gérer les notifications
CREATE TABLE notification (
  id INT NOT NULL AUTO_INCREMENT,
  utilisateur_matricule INT NOT NULL, -- Modification : Utilisation du matricule comme clé étrangère
  message TEXT NOT NULL,
  lu BOOLEAN NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule)
);

-- Table pour enregistrer l'historique des modifications
CREATE TABLE historique_modifications (
  id INT NOT NULL AUTO_INCREMENT,
  utilisateur_matricule INT NOT NULL, -- Modification : Utilisation du matricule comme clé étrangère
  date_modification DATETIME NOT NULL,
  action_effectuee TEXT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule)
);

-- Création de la table filiere
CREATE TABLE filiere (
  id INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(255) NOT NULL,
  description TEXT,
  PRIMARY KEY (id)
);

-- Table pour stocker des informations spécifiques aux étudiants
CREATE TABLE information_etudiant (
  utilisateur_matricule INT NOT NULL, -- Modification : Utilisation du matricule comme clé étrangère
  filiere_id INT NOT NULL,
  PRIMARY KEY (utilisateur_matricule),
  FOREIGN KEY (utilisateur_matricule) REFERENCES utilisateur (matricule),
  FOREIGN KEY (filiere_id) REFERENCES filiere (id)
);

-- ... (d'autres tables peuvent être ajoutées en fonction des besoins spécifiques)
