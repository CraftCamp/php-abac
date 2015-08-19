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

-- --------------------------------------------------------

--
-- Structure de la table `abac_attributes`
--

DROP TABLE IF EXISTS `abac_attributes`;
CREATE TABLE IF NOT EXISTS `abac_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `column_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `criteria_column` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Contenu de la table `abac_attributes`
--

INSERT INTO `abac_attributes` (`id`, `table_name`, `column_name`, `criteria_column`, `created_at`, `updated_at`, `name`) VALUES
(1, 'user', 'age', 'id', '2015-08-19 11:03:38', '2015-08-19 11:03:38', 'age'),
(2, 'user', 'parent_nationality', 'id', '2015-08-19 11:03:38', '2015-08-19 11:03:38', 'nationalitÃ©'),
(3, 'user', 'has_done_japd', 'id', '2015-08-19 11:03:38', '2015-08-19 11:03:38', 'JAPD');

-- --------------------------------------------------------

--
-- Structure de la table `abac_policy_rules_attributes`
--

DROP TABLE IF EXISTS `abac_policy_rules_attributes`;
CREATE TABLE IF NOT EXISTS `abac_policy_rules_attributes` (
  `policy_rule_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `comparison` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  KEY `policy_rule_id` (`policy_rule_id`,`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `abac_policy_rules_attributes`
--

INSERT INTO `abac_policy_rules_attributes` (`policy_rule_id`, `attribute_id`, `comparison`, `value`) VALUES
(1, 1, 'greaterThan', '18'),
(1, 2, 'equal', 'FR'),
(1, 3, 'equal', '1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;