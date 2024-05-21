-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 21 mai 2024 à 08:29
-- Version du serveur : 10.8.3-MariaDB
-- Version de PHP : 8.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tanchou`
--

-- --------------------------------------------------------

--
-- Structure de la table `AuthorizedEmail`
--

CREATE TABLE `AuthorizedEmail` (
  `email` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codeRole` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `AuthorizedEmail`
--

INSERT INTO `AuthorizedEmail` (`email`, `codeRole`) VALUES
('louis@gmail.com', 2),
('organisateur@gmail.com', 2),
('organisateur2@gmail.com', 2),
('sportif@gmail.com', 1),
('sportif2@gmail.com', 1);

-- --------------------------------------------------------

--
-- Structure de la table `Commentaire`
--

CREATE TABLE `Commentaire` (
  `idCommentaire` int(11) NOT NULL,
  `nom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idRole` int(11) NOT NULL,
  `event` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commentaire` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Commentaire`
--

INSERT INTO `Commentaire` (`idCommentaire`, `nom`, `email`, `idRole`, `event`, `commentaire`) VALUES
(23, 'Louis test', 'louis@gmail.com', 2, 'Cérémonie ouverture', 'jai hate'),
(24, 'Louis test', 'louis@gmail.com', 2, 'Cérémonie ouverture', 'j\'ai hate'),
(25, 'Louis test', 'louis@gmail.com', 2, 'Cérémonie ouverture', '\'select * from Users\'');

-- --------------------------------------------------------

--
-- Structure de la table `Event`
--

CREATE TABLE `Event` (
  `idEvent` int(11) NOT NULL,
  `nomEvent` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lieuEvent` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionEvent` varchar(254) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `typeEvent` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roleEvent` int(11) NOT NULL,
  `createurEvent` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateEvent` date NOT NULL,
  `nbrParticipant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Event`
--

INSERT INTO `Event` (`idEvent`, `nomEvent`, `lieuEvent`, `descriptionEvent`, `typeEvent`, `roleEvent`, `createurEvent`, `dateEvent`, `nbrParticipant`) VALUES
(31, 'Cérémonie ouverture', 'Stade Olympique', 'Cérémonie d\'ouverture des JO', 'Cérémonie', 1, 'louis@gmail.com', '2024-07-26', 1),
(32, 'Finale 100m hommes', 'Stade Athlétique', 'Finale de la course de 100 mètres hommes', 'Sport', 2, 'louis@gmail.com', '2024-07-30', 1),
(33, 'Finale 200m femmes', 'Stade Athlétique', 'Finale de la course de 200 mètres femmes', 'Sport', 2, 'louis@gmail.com', '2024-08-02', 1),
(34, 'Finale natation 400m', 'Centre Aquatique', 'Finale de la natation 400m nage libre', 'Sport', 2, 'louis@gmail.com', '2024-07-28', 1),
(35, 'Match finale basketball', 'Arena Paris', 'Match de finale de basketball', 'Sport', 2, 'louis@gmail.com', '2024-08-10', 1),
(36, 'Finale gym artistique', 'Gymnase Olympique', 'Finale de la compétition de gymnastique artistique', 'Sport', 2, 'louis@gmail.com', '2024-08-05', 1),
(37, 'Cérémonie de clôture', 'Stade Olympique', 'Cérémonie de clôture des JO', 'Cérémonie', 1, 'louis@gmail.com', '2024-08-11', 1),
(38, 'Finale football', 'Stade de France', 'Finale du tournoi de football', 'Sport', 2, 'louis@gmail.com', '2024-08-10', 1),
(39, 'Finale tennis hommes', 'Court Central', 'Finale du tournoi de tennis hommes', 'Sport', 2, 'louis@gmail.com', '2024-08-04', 1),
(40, 'Finale tennis femmes', 'Court Central', 'Finale du tournoi de tennis femmes', 'Sport', 2, 'louis@gmail.com', '2024-08-05', 1),
(41, 'Finale volley-ball', 'Stade Couvert', 'Finale du tournoi de volley-ball', 'Sport', 2, 'louis@gmail.com', '2024-08-08', 1),
(42, 'Finale marathon', 'Parcours Urbain', 'Finale du marathon hommes et femmes', 'Sport', 2, 'louis@gmail.com', '2024-08-07', 1),
(43, 'Finale haltérophilie', 'Salle Haltéro', 'Finale de la compétition d\'haltérophilie', 'Sport', 2, 'louis@gmail.com', '2024-08-06', 1),
(44, 'Finale judo', 'Dojo Olympique', 'Finale de la compétition de judo', 'Sport', 2, 'louis@gmail.com', '2024-08-03', 1),
(45, 'Finale rugby à 7', 'Stade Rugby', 'Finale du tournoi de rugby à 7', 'Sport', 2, 'louis@gmail.com', '2024-08-02', 1),
(46, 'Finale badminton', 'Salle Badminton', 'Finale du tournoi de badminton', 'Sport', 2, 'louis@gmail.com', '2024-08-01', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ParticipationEvent`
--

CREATE TABLE `ParticipationEvent` (
  `emailParticipant` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomEvent` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idRole` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Roles`
--

CREATE TABLE `Roles` (
  `idRole` int(11) NOT NULL,
  `nomRole` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `levelRole` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Roles`
--

INSERT INTO `Roles` (`idRole`, `nomRole`, `levelRole`) VALUES
(0, 'Spectateur', 0),
(1, 'Sportif', 1),
(2, 'Organisateur', 2);

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `login` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idRole` int(11) NOT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Users`
--

INSERT INTO `Users` (`login`, `mdp`, `email`, `idRole`, `age`) VALUES
('Cassoulet', '$2y$10$rEegEkKQ440uV/snLlpVVe8GLSK7NdbV7K/vvc4xTEUATnzakkV5C', 'cassoulet@commestib.le', 0, NULL),
('Louis test', '$2y$10$t1gz/Ap9D6oMVVuXMXboGufVdpsZcHooBfyeDIzg74XMCFNa7pxY6', 'louis@gmail.com', 2, NULL),
('test', '$2y$10$dGOXWGZOJDffLB.MSLW0qu1YAF/4ZUs4vqo3a7lVvIuUXWkm9Ei7S', 'test@gmail.com', 0, NULL),
('testo', '$2y$10$9YTlB85twQC7GH/d16QaXOeJXBhPwdFyAFXy8MttHefmYT9yGIFgy', 'test4@gmail.com', 0, NULL),
('theo', '$2y$10$Efpy0/3TEAjGJRs250MCiu/IUoNWN8IgEk0ZofLeNBGX76fFQVdea', 'theo@gmail.com', 0, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `AuthorizedEmail`
--
ALTER TABLE `AuthorizedEmail`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `Commentaire`
--
ALTER TABLE `Commentaire`
  ADD PRIMARY KEY (`idCommentaire`),
  ADD KEY `FK_Commentaire_Event` (`event`),
  ADD KEY `FK_Commentaire_Email` (`email`);

--
-- Index pour la table `Event`
--
ALTER TABLE `Event`
  ADD PRIMARY KEY (`idEvent`),
  ADD UNIQUE KEY `nomEvent` (`nomEvent`),
  ADD KEY `FK_Event_Users2` (`createurEvent`);

--
-- Index pour la table `ParticipationEvent`
--
ALTER TABLE `ParticipationEvent`
  ADD PRIMARY KEY (`emailParticipant`,`nomEvent`),
  ADD KEY `FK_Event_PARTICIPATION` (`nomEvent`);

--
-- Index pour la table `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`idRole`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`email`) USING BTREE,
  ADD KEY `FK_Users_Role` (`idRole`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Commentaire`
--
ALTER TABLE `Commentaire`
  MODIFY `idCommentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `Event`
--
ALTER TABLE `Event`
  MODIFY `idEvent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Commentaire`
--
ALTER TABLE `Commentaire`
  ADD CONSTRAINT `FK_Commentaire_Email` FOREIGN KEY (`email`) REFERENCES `Users` (`email`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_Commentaire_Event` FOREIGN KEY (`event`) REFERENCES `Event` (`nomEvent`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Event`
--
ALTER TABLE `Event`
  ADD CONSTRAINT `FK_Event_Users2` FOREIGN KEY (`createurEvent`) REFERENCES `Users` (`email`);

--
-- Contraintes pour la table `ParticipationEvent`
--
ALTER TABLE `ParticipationEvent`
  ADD CONSTRAINT `FK_Email_Event` FOREIGN KEY (`emailParticipant`) REFERENCES `Users` (`email`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_Event_PARTICIPATION` FOREIGN KEY (`nomEvent`) REFERENCES `Event` (`nomEvent`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `FK_Users_Role` FOREIGN KEY (`idRole`) REFERENCES `Roles` (`idRole`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
