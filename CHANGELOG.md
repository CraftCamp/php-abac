# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Added
- Null comparison
- Not null comparison
- Chained attributes example and documentation

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
