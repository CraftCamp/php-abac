# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [2.1.2] - 2018-01-31
### Added
- Support of Symfony 4 components

### Fixed
- Code style (PSR-2 compliant)

## [2.1.1] - 2016-12-12
### Added
- JSON configuration file compatibility
- Using Symfony Locator for configuration file
- Multiple Set of Rules for an unique Rule name
- Allow simple configuration by include file ( via @import attribute )
- Allow to specify getter prefix (default = get ) and method to apply on getter name method instead of ucfist(default)
- Allow addional parameters for getters in config file.

## [2.1.0] - 2016-10-09
### Added
- Text cache driver
- Cache folder configuration for cache files
- Resource field comparison refering to user attribute
- User field comparison refering to resource attribute

### Changed
- Auxiliary comparisons made by a comparison class does not generate rejected attributes anymore

## [2.0.3] - 2016-06-04
### Changed
- The comparison manager handles dynamic attributes instead of ABAC class
- The comparison manager handles rejected attributes and result instead of ABAC class

### Fixed
- Chained attributes can return null in case of unset object in the chain
- Dynamic attributes for contained attributes

## [2.0.2] - 2016-06-03
### Added
- Null comparison
- Not null comparison
- Chained attributes example and documentation

### Fixed
- Code example in documentation

## [2.0.1] - 2016-06-02
### Added
- Containing comparison for arrays
- Extra data for policy rule attributes

## [2.0.0] - 2016-05-26
### Added
- Configuration manager
- YAML Loader for configuration files
- Multiple configuration files loading
- Example classes for example script

### Changed
- Rules and attributes are now defined with configuration file instead of database
- The enforce method accepts objects instead of numeric IDs
- Attributes are now accessed from the objects instead of the database

### Removed
- Rules and attributes creation by manager

## [1.2.0] - 2016-04-20
### Added
- PSR-6 compliant cache implementation
- Memory cache driver

### Changed
- Dynamic attributes are now an enforce method option

### Fixed
- Example script database connection
- Support lowercase for comparison type values

## [1.1] - 2015-11-17
### Added
- Scrutinizer CI
- Travis CI
- SQLite for unit tests

### Changed
- Perform PHP-CS-Fixer to apply PSR-2

### Fixed
- PHP 5.4 support

## [1.0] - 2015-11-16
### Added
* POC file example.php
* Environment Attributes
* Dynamic Attributes

### Changed
* enforce() method to accept dynamic and environment attributes
* Tables structure (optimized with foreign keys)

### Fixed
* Policy Rule creation
* Attribute creation

## [0.3] - 2015-08-25
### Added
* Comparison classes
* enforce() method to take access-control decisions

### Changed
* Attributes model to implement comparison

### Removed
* Abac resetSchema method (replaced by fixtures)

## [0.2] - 2015-08-05
### Added
* Policy Rule creation
* Policy Rules manager
* Policy Rules repository
* Policy Rules model
* Attributes manager
* Attributes repository
* Attributes model
* SQL schema dump
