DROP TABLE IF EXISTS `abac_policy_rules_attributes`;
DROP TABLE IF EXISTS `abac_policy_rules`;
DROP TABLE IF EXISTS `abac_environment_attributes`;
DROP TABLE IF EXISTS `abac_attributes`;
DROP TABLE IF EXISTS `abac_attributes_data`;
DROP TABLE IF EXISTS `abac_test_vehicle`;
DROP TABLE IF EXISTS `abac_test_user`;

CREATE TABLE IF NOT EXISTS `abac_policy_rules` (
  `id` integer PRIMARY KEY AUTOINCREMENT,
  `name` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
);

CREATE TABLE IF NOT EXISTS `abac_attributes_data` (
  `id` integer PRIMARY KEY AUTOINCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS `abac_attributes` (
  `id` integer PRIMARY KEY AUTOINCREMENT,
  `property` varchar(40) NOT NULL,
  FOREIGN KEY(id) REFERENCES abac_attributes_data(id)
);

CREATE TABLE IF NOT EXISTS `abac_environment_attributes` (
  `id` integer PRIMARY KEY AUTOINCREMENT,
  `variable_name` varchar(65) NOT NULL,
  FOREIGN KEY(id) REFERENCES abac_attributes_data(id)
);

CREATE TABLE IF NOT EXISTS `abac_policy_rules_attributes` (
  `policy_rule_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `type` VARCHAR(15) NOT NULL,
  `comparison_type` varchar(10) NOT NULL,
  `comparison` varchar(60) NOT NULL,
  `value` varchar(255) NOT NULL,
  FOREIGN KEY(policy_rule_id) REFERENCES abac_policy_rules(id),
  FOREIGN KEY(attribute_id) REFERENCES abac_attributes_data(id)
);

INSERT INTO `abac_policy_rules` (`id`, `name`, `created_at`, `updated_at`) VALUES (1, 'nationality-access', '2015-08-14 13:51:06', '2015-08-14 13:51:06');
INSERT INTO `abac_policy_rules` (`id`, `name`, `created_at`, `updated_at`) VALUES (2, 'vehicle-homologation', '2015-07-27 05:45:00', '2015-07-27 05:45:00');
INSERT INTO `abac_policy_rules` (`id`, `name`, `created_at`, `updated_at`) VALUES (3, 'gunlaw', '2015-08-16 16:21:10', '2015-08-16 16:21:10');

INSERT INTO `abac_attributes_data` (`id`, `created_at`, `updated_at`, `name`, `slug`) VALUES (1, '2015-08-19 11:03:38', '2015-08-19 11:03:38', 'Age', 'age');
INSERT INTO `abac_attributes_data` (`id`, `created_at`, `updated_at`, `name`, `slug`) VALUES (2, '2015-08-19 11:03:38', '2015-08-19 11:03:38', 'Nationalité des Parents', 'nationalite-des-parents');
INSERT INTO `abac_attributes_data` (`id`, `created_at`, `updated_at`, `name`, `slug`) VALUES (3, '2015-08-19 11:03:38', '2015-08-19 11:03:38', 'JAPD', 'japd');
INSERT INTO `abac_attributes_data` (`id`, `created_at`, `updated_at`, `name`, `slug`) VALUES (4, '2015-08-19 11:03:38', '2015-08-19 11:03:38', 'Permis de Conduire', 'permis-de-conduire');
INSERT INTO `abac_attributes_data` (`id`, `created_at`, `updated_at`, `name`, `slug`) VALUES (5, '2015-08-19 11:03:38', '2015-08-19 11:03:38', 'Dernier Contrôle Technique', 'dernier-controle-technique');
INSERT INTO `abac_attributes_data` (`id`, `created_at`, `updated_at`, `name`, `slug`) VALUES (6, '2015-08-19 11:03:38', '2015-08-19 11:03:38', 'Date de sortie usine', 'date-de-sortie-d-usine');
INSERT INTO `abac_attributes_data` (`id`, `created_at`, `updated_at`, `name`, `slug`) VALUES (7, '2015-08-19 11:03:38', '2015-08-19 11:03:38', 'Origine', 'origine');
INSERT INTO `abac_attributes_data` (`id`, `created_at`, `updated_at`, `name`, `slug`) VALUES (8, '2015-08-19 11:03:38', '2015-08-19 11:03:38', 'Propriétaire', 'proprietaire');
INSERT INTO `abac_attributes_data` (`id`, `created_at`, `updated_at`, `name`, `slug`) VALUES (9, '2015-08-19 11:03:38', '2015-08-19 11:03:38', 'Statut du service', 'statut-du-service');

INSERT INTO `abac_attributes` (`id`, `property`) VALUES (1, 'age');
INSERT INTO `abac_attributes` (`id`, `property`) VALUES (2, 'parentNationality');
INSERT INTO `abac_attributes` (`id`, `property`) VALUES (3, 'hasDoneJapd');
INSERT INTO `abac_attributes` (`id`, `property`) VALUES (4, 'hasDrivingLicense');
INSERT INTO `abac_attributes` (`id`, `property`) VALUES (5, 'lastTechnicalReviewDate');
INSERT INTO `abac_attributes` (`id`, `property`) VALUES (6, 'manufactureDate');
INSERT INTO `abac_attributes` (`id`, `property`) VALUES (7, 'origin');
INSERT INTO `abac_attributes` (`id`, `property`) VALUES (8, 'owner.id');

INSERT INTO `abac_environment_attributes` (`id`, `variable_name`) VALUES (9, 'SERVICE_STATE');

INSERT INTO `abac_policy_rules_attributes` (`policy_rule_id`, `attribute_id`, `type`, `comparison_type`, `comparison`, `value`) VALUES (1, 1, 'user', 'Numeric', 'isGreaterThan', '18');
INSERT INTO `abac_policy_rules_attributes` (`policy_rule_id`, `attribute_id`, `type`, `comparison_type`, `comparison`, `value`) VALUES (1, 2, 'user', 'String', 'isEqual', 'FR');
INSERT INTO `abac_policy_rules_attributes` (`policy_rule_id`, `attribute_id`, `type`, `comparison_type`, `comparison`, `value`) VALUES (1, 3, 'user', 'Numeric', 'isEqual', '1');
INSERT INTO `abac_policy_rules_attributes` (`policy_rule_id`, `attribute_id`, `type`, `comparison_type`, `comparison`, `value`) VALUES (2, 4, 'user', 'Boolean', 'boolAnd', '1');
INSERT INTO `abac_policy_rules_attributes` (`policy_rule_id`, `attribute_id`, `type`, `comparison_type`, `comparison`, `value`) VALUES (2, 5, 'object', 'Date', 'isMoreRecentThan', '2Y');
INSERT INTO `abac_policy_rules_attributes` (`policy_rule_id`, `attribute_id`, `type`, `comparison_type`, `comparison`, `value`) VALUES (2, 6, 'object', 'Date', 'isMoreRecentThan', '25Y');
INSERT INTO `abac_policy_rules_attributes` (`policy_rule_id`, `attribute_id`, `type`, `comparison_type`, `comparison`, `value`) VALUES (2, 7, 'object', 'Array', 'isIn', 'a:5:{i:0;s:2:"FR";i:1;s:2:"DE";i:2;s:2:"IT";i:3;s:1:"L";i:4;s:2:"GB";}');
INSERT INTO `abac_policy_rules_attributes` (`policy_rule_id`, `attribute_id`, `type`, `comparison_type`, `comparison`, `value`) VALUES (2, 8, 'object', 'Numeric', 'isEqual', 'dynamic');
INSERT INTO `abac_policy_rules_attributes` (`policy_rule_id`, `attribute_id`, `type`, `comparison_type`, `comparison`, `value`) VALUES (2, 9, 'environment', 'String', 'isEqual', 'OPEN');
