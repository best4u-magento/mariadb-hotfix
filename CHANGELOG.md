# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2026-11-10

### Added
- Added this `CHANGELOG.md` file.
- Documented in `README.md` which Magento versions need the module (2.4.4 – 2.4.8) and that Magento 2.4.9 supports these MariaDB versions natively.
- Declared compatibility with PHP 8.1, 8.2, 8.3, 8.4 and 8.5.
- Added unit tests for the NULL default fix.
- Added a `.gitignore` file.

### Changed
- `magento/framework` constraint changed from `>=103.0.0 <=103.0.9` to `~103.0.4` (Magento 2.4.4 and later). The old constraint excluded future 2.4.9 patch releases (e.g. `103.0.9-p1`), which would block upgrades of shops that still have the module installed; on 2.4.9 the module has no effect.
- The NULL default plugin uses constructor property promotion; the check whether Magento handles MariaDB 11+ itself is a separate method.
- Shortened the installation and uninstallation instructions (no static content deployment needed).

### Removed
- Removed the obsolete `setup_version` attribute and the empty `<sequence/>` from `etc/module.xml`.

### Fixed
- The MariaDB 11 version pattern `^11\.[4|8]\.` used a character class that also matched a literal `|`; changed to `^11\.(4|8)\.`.
- Fixed the broken copyright character (`Â©`) in `etc/module.xml`.

## [1.0.0] - 2026-07-16

### Added
- Initial release: MariaDB 11 and 12 support for Magento 2.4.0 – 2.4.8 (supported database version patterns and NULL column default handling).
