-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 23 mars 2021 à 18:41
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pelicandb`
--

-- --------------------------------------------------------

--
-- Structure de la table `community`
--

DROP TABLE IF EXISTS `community`;
CREATE TABLE IF NOT EXISTS `community` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `date_suppression` datetime DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `community`
--

INSERT INTO `community` (`id`, `date_creation`, `date_modification`, `date_suppression`, `name`) VALUES
(1, '2021-02-22 11:17:55', '2021-02-22 11:17:55', NULL, 'Phantom Blood'),
(2, '2021-02-22 11:18:02', '2021-02-22 11:18:02', NULL, 'Stardust Crusaders');

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `date_suppression` datetime DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `nb_participant` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3BAE0AA7A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `friendship`
--

DROP TABLE IF EXISTS `friendship`;
CREATE TABLE IF NOT EXISTS `friendship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_user_id` int(11) DEFAULT NULL,
  `second_user_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `validate` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_friendship_unique` (`first_user_id`,`second_user_id`),
  KEY `IDX_7234A45FB4E2BF69` (`first_user_id`),
  KEY `IDX_7234A45FB02C53F8` (`second_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `friendship`
--

INSERT INTO `friendship` (`id`, `first_user_id`, `second_user_id`, `date`, `validate`) VALUES
(14, 9, 7, '2021-02-20 10:45:29', 1),
(15, 9, 4, '2021-02-20 10:45:31', 1),
(16, 9, 8, '2021-02-20 10:45:33', 0),
(17, 9, 3, '2021-02-20 10:45:35', 1);

-- --------------------------------------------------------

--
-- Structure de la table `isincommunity`
--

DROP TABLE IF EXISTS `isincommunity`;
CREATE TABLE IF NOT EXISTS `isincommunity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `community_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_participation_unique` (`user_id`,`community_id`),
  KEY `IDX_CC180DD3A76ED395` (`user_id`),
  KEY `IDX_CC180DD3FDA7B0BF` (`community_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `isincommunity`
--

INSERT INTO `isincommunity` (`id`, `user_id`, `community_id`, `date`) VALUES
(1, 9, 2, '2021-02-22 11:31:23');

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `seen` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BF5476CAA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notification`
--

INSERT INTO `notification` (`id`, `user_id`, `title`, `link`, `description`, `seen`) VALUES
(1, 7, 'Martin a créé un évenement', 'event', 'description de création d\'évenement', 0),
(2, 4, 'Martin a créé un évenement', 'event', 'description de création d\'évenement', 0),
(3, 3, 'Martin a créé un évenement', 'event', 'description de création d\'évenement', 0);

-- --------------------------------------------------------

--
-- Structure de la table `participations`
--

DROP TABLE IF EXISTS `participations`;
CREATE TABLE IF NOT EXISTS `participations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_participation_unique` (`user_id`,`event_id`),
  KEY `IDX_FDC6C6E8A76ED395` (`user_id`),
  KEY `IDX_FDC6C6E871F7E88B` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dark_mode` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`id`, `language`, `dark_mode`) VALUES
(3, 'fr_FR', 0),
(4, 'fr_FR', 0),
(7, 'fr_FR', 0),
(8, 'fr_FR', 0),
(9, 'fr_FR', 1),
(10, 'fr_FR', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settings_id` int(11) DEFAULT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  UNIQUE KEY `UNIQ_8D93D64959949888` (`settings_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `settings_id`, `email`, `roles`, `city`, `pseudo`, `password`, `image`) VALUES
(3, 3, 'camille.thb@outlook.fr', '[]', 'Chanceaux sur choisilles', 'Camille', '$argon2id$v=19$m=65536,t=4,p=1$Q1REM04yUmsvWUdrTklXOQ$C/sHR9tvTbzvDkpuep8Li1QAQOpNCymqoeWdsABz3Vo', '42c8e54808aa886fcca9a471d33501b747ed9e0b82183f4a12.jpg'),
(4, 4, 'f.laugere37@gmail.com', '[]', 'Reugny', 'Arsouille', '$argon2id$v=19$m=65536,t=4,p=1$MXYyNXlGRDA2dEdxMm8vaQ$clrZdxR6+cMb20NJIAbs9y8aWXUtky1GPq6sqKXHZLE', '1bc84078784d05fa0546797353284a892f01dad72f61b3a29c.jpg'),
(7, 7, 'admin@admin', '[]', 'AdminLand', 'Admin', '$argon2id$v=19$m=65536,t=4,p=1$ZHBLek1UQjMvZWJQN2pTdA$pSkcnVLXhW1Gbps0z1onZZMGRKpnd5JHanoS2S2kOoQ', 'ayuizedteè_e-éa_è_agd.jpg'),
(8, 8, 'dubois.simon19@gmail.com', '[]', 'Laillé', 'relyzz', '$argon2id$v=19$m=65536,t=4,p=1$MlNHdWw5VjdBY3hVTExVbQ$3JBSG2RJOlJZgPXBq2IShSPOVPByijQvG4KRxzaT/8I', '7367eaba9d78cfb9e1eb824f04eb86ca32cc85fc5ecb16b25b.jpg'),
(9, 9, 'martinlaugere37@gmail.com', '[]', 'Nantes', 'Martin', '$argon2id$v=19$m=65536,t=4,p=1$ZktTRnEuVWtoVnkwTnp2dA$0SFpSOeh9mOL/44pyHOarOBcbnz1qEuKfLr5ABDD6Lo', 'ada022ffdfce2bef68d832f6b68a4009953e5cd799952e123b.jpg'),
(10, 10, 'test@test.fr', '[]', 'TestLand', 'Test', '$argon2id$v=19$m=65536,t=4,p=1$RmJZRUNQWlBjNFBkNUxRYQ$2M/cv6qBGZcmFoXs9T/Os32qZQhIiTn4L/2jyInGdRY', '35067104b420537875f1194c56b3276822a101388b21af1782.png');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `FK_3BAE0AA7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `friendship`
--
ALTER TABLE `friendship`
  ADD CONSTRAINT `FK_7234A45FB02C53F8` FOREIGN KEY (`second_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_7234A45FB4E2BF69` FOREIGN KEY (`first_user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `isincommunity`
--
ALTER TABLE `isincommunity`
  ADD CONSTRAINT `FK_CC180DD3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_CC180DD3FDA7B0BF` FOREIGN KEY (`community_id`) REFERENCES `community` (`id`);

--
-- Contraintes pour la table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `FK_BF5476CAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `participations`
--
ALTER TABLE `participations`
  ADD CONSTRAINT `FK_FDC6C6E871F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `FK_FDC6C6E8A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64959949888` FOREIGN KEY (`settings_id`) REFERENCES `settings` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
