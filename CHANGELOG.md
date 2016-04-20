# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

### [Unreleased]
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