-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 18 Août 2015 à 12:38
-- Version du serveur: 5.5.44-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `php_abac_test`
--

-- --------------------------------------------------------

--
-- Structure de la table `abac_policy_rules`
--
DROP TABLE IF EXISTS `abac_policy_rules`;
CREATE TABLE IF NOT EXISTS `abac_policy_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Contenu de la table `abac_policy_rules`
--

INSERT INTO `abac_policy_rules` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'nationality-access', '2015-08-14 13:51:06', '2015-08-14 13:51:06');
INSERT INTO `abac_policy_rules` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'test-rule', '2015-07-27 05:45:00', '2015-07-27 05:45:00');
INSERT INTO `abac_policy_rules` (`id`, `name`, `created_at`, `updated_at`) VALUES
(3, 'gunlaw', '2015-08-16 16:21:10', '2015-08-16 16:21:10');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;