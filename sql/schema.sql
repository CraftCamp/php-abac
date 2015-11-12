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
CREATE TABLE IF NOT EXISTS `abac_policy_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `abac_attributes`
--

CREATE TABLE IF NOT EXISTS `abac_attributes_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `abac_attributes`
--

CREATE TABLE IF NOT EXISTS `abac_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `column_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `criteria_column` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `abac_attributes`
--

CREATE TABLE IF NOT EXISTS `abac_environment_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `variable_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `abac_policy_rules_attributes`
--

CREATE TABLE IF NOT EXISTS `abac_policy_rules_attributes` (
  `policy_rule_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `type` VARCHAR(15) COLLATE utf8_unicode_ci NOT NULL,
  `comparison_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `comparison` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  KEY `policy_rule_id` (`policy_rule_id`,`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `abac_attributes`
--
ALTER TABLE `abac_policy_rules_attributes`
  ADD CONSTRAINT `policy_rules` FOREIGN KEY (`policy_rule_id`) REFERENCES `abac_policy_rules` (`id`);
ALTER TABLE `abac_policy_rules_attributes`
  ADD CONSTRAINT `attributes` FOREIGN KEY (`attribute_id`) REFERENCES `abac_attributes_data` (`id`);
--
-- Contraintes pour la table `abac_attributes`
--
ALTER TABLE `abac_attributes`
  ADD CONSTRAINT `attributes_data` FOREIGN KEY (`id`) REFERENCES `abac_attributes_data` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `abac_environment_attributes`
--
ALTER TABLE `abac_environment_attributes`
  ADD CONSTRAINT `environment_attributes_data` FOREIGN KEY (`id`) REFERENCES `abac_attributes_data` (`id`) ON DELETE CASCADE;

