-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 29 déc. 2023 à 11:16
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestpresence`
--

-- --------------------------------------------------------

--
-- Structure de la table `attribution_cours`
--

CREATE TABLE `attribution_cours` (
  `id` int(11) NOT NULL,
  `utilisateur_matricule` int(11) NOT NULL,
  `cours_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

CREATE TABLE `cours` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `enregistrement_assiduite`
--

CREATE TABLE `enregistrement_assiduite` (
  `id` int(11) NOT NULL,
  `utilisateur_matricule` int(11) NOT NULL,
  `cours_id` int(11) NOT NULL,
  `session_academique_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `present` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `filiere`
--

CREATE TABLE `filiere` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique_modifications`
--

CREATE TABLE `historique_modifications` (
  `id` int(11) NOT NULL,
  `utilisateur_matricule` int(11) NOT NULL,
  `date_modification` datetime NOT NULL,
  `action_effectuee` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `information_etudiant`
--

CREATE TABLE `information_etudiant` (
  `utilisateur_matricule` int(11) NOT NULL,
  `filiere_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `justificatif`
--

CREATE TABLE `justificatif` (
  `id` int(11) NOT NULL,
  `utilisateur_matricule` int(11) NOT NULL,
  `enregistrement_assiduite_id` int(11) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `fichier` varchar(255) DEFAULT NULL,
  `statut` enum('en_attente','valide','rejete') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `utilisateur_matricule` int(11) NOT NULL,
  `message` text NOT NULL,
  `lu` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `profil`
--

CREATE TABLE `profil` (
  `id` int(11) NOT NULL,
  `utilisateur_matricule` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `date_naissance` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `retard`
--

CREATE TABLE `retard` (
  `id` int(11) NOT NULL,
  `utilisateur_matricule` int(11) NOT NULL,
  `cours_id` int(11) NOT NULL,
  `session_academique_id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE `session` (
  `id` int(11) NOT NULL,
  `utilisateur_matricule` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `session_academique`
--

CREATE TABLE `session_academique` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `matricule` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('etudiant','enseignant','administrateur') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `attribution_cours`
--
ALTER TABLE `attribution_cours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_matricule` (`utilisateur_matricule`),
  ADD KEY `cours_id` (`cours_id`);

--
-- Index pour la table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `enregistrement_assiduite`
--
ALTER TABLE `enregistrement_assiduite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_matricule` (`utilisateur_matricule`),
  ADD KEY `cours_id` (`cours_id`),
  ADD KEY `session_academique_id` (`session_academique_id`);

--
-- Index pour la table `filiere`
--
ALTER TABLE `filiere`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `historique_modifications`
--
ALTER TABLE `historique_modifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_matricule` (`utilisateur_matricule`);

--
-- Index pour la table `information_etudiant`
--
ALTER TABLE `information_etudiant`
  ADD PRIMARY KEY (`utilisateur_matricule`),
  ADD KEY `filiere_id` (`filiere_id`);

--
-- Index pour la table `justificatif`
--
ALTER TABLE `justificatif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_matricule` (`utilisateur_matricule`),
  ADD KEY `enregistrement_assiduite_id` (`enregistrement_assiduite_id`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_matricule` (`utilisateur_matricule`);

--
-- Index pour la table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_matricule` (`utilisateur_matricule`);

--
-- Index pour la table `retard`
--
ALTER TABLE `retard`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_matricule` (`utilisateur_matricule`),
  ADD KEY `cours_id` (`cours_id`),
  ADD KEY `session_academique_id` (`session_academique_id`);

--
-- Index pour la table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_matricule` (`utilisateur_matricule`);

--
-- Index pour la table `session_academique`
--
ALTER TABLE `session_academique`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`matricule`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `attribution_cours`
--
ALTER TABLE `attribution_cours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cours`
--
ALTER TABLE `cours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `enregistrement_assiduite`
--
ALTER TABLE `enregistrement_assiduite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `filiere`
--
ALTER TABLE `filiere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historique_modifications`
--
ALTER TABLE `historique_modifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `justificatif`
--
ALTER TABLE `justificatif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `profil`
--
ALTER TABLE `profil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `retard`
--
ALTER TABLE `retard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `session`
--
ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `session_academique`
--
ALTER TABLE `session_academique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `attribution_cours`
--
ALTER TABLE `attribution_cours`
  ADD CONSTRAINT `attribution_cours_ibfk_1` FOREIGN KEY (`utilisateur_matricule`) REFERENCES `utilisateur` (`matricule`),
  ADD CONSTRAINT `attribution_cours_ibfk_2` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`);

--
-- Contraintes pour la table `enregistrement_assiduite`
--
ALTER TABLE `enregistrement_assiduite`
  ADD CONSTRAINT `enregistrement_assiduite_ibfk_1` FOREIGN KEY (`utilisateur_matricule`) REFERENCES `utilisateur` (`matricule`),
  ADD CONSTRAINT `enregistrement_assiduite_ibfk_2` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`),
  ADD CONSTRAINT `enregistrement_assiduite_ibfk_3` FOREIGN KEY (`session_academique_id`) REFERENCES `session_academique` (`id`);

--
-- Contraintes pour la table `historique_modifications`
--
ALTER TABLE `historique_modifications`
  ADD CONSTRAINT `historique_modifications_ibfk_1` FOREIGN KEY (`utilisateur_matricule`) REFERENCES `utilisateur` (`matricule`);

--
-- Contraintes pour la table `information_etudiant`
--
ALTER TABLE `information_etudiant`
  ADD CONSTRAINT `information_etudiant_ibfk_1` FOREIGN KEY (`utilisateur_matricule`) REFERENCES `utilisateur` (`matricule`),
  ADD CONSTRAINT `information_etudiant_ibfk_2` FOREIGN KEY (`filiere_id`) REFERENCES `filiere` (`id`);

--
-- Contraintes pour la table `justificatif`
--
ALTER TABLE `justificatif`
  ADD CONSTRAINT `justificatif_ibfk_1` FOREIGN KEY (`utilisateur_matricule`) REFERENCES `utilisateur` (`matricule`),
  ADD CONSTRAINT `justificatif_ibfk_2` FOREIGN KEY (`enregistrement_assiduite_id`) REFERENCES `enregistrement_assiduite` (`id`);

--
-- Contraintes pour la table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`utilisateur_matricule`) REFERENCES `utilisateur` (`matricule`);

--
-- Contraintes pour la table `profil`
--
ALTER TABLE `profil`
  ADD CONSTRAINT `profil_ibfk_1` FOREIGN KEY (`utilisateur_matricule`) REFERENCES `utilisateur` (`matricule`);

--
-- Contraintes pour la table `retard`
--
ALTER TABLE `retard`
  ADD CONSTRAINT `retard_ibfk_1` FOREIGN KEY (`utilisateur_matricule`) REFERENCES `utilisateur` (`matricule`),
  ADD CONSTRAINT `retard_ibfk_2` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`),
  ADD CONSTRAINT `retard_ibfk_3` FOREIGN KEY (`session_academique_id`) REFERENCES `session_academique` (`id`);

--
-- Contraintes pour la table `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`utilisateur_matricule`) REFERENCES `utilisateur` (`matricule`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
