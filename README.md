# Best4u MariaDB Hotfix Module

Adds MariaDB 11 and 12 compatibility for Magento 2.4.0-2.4.8.
This module provides the required adjustments to allow Magento to run on newer MariaDB versions that are not officially supported by these Magento releases.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Support](#support)
- [License](#license)

## Requirements

- Magento 2.4.0-2.4.8.

## Installation

```bash
composer config repositories.best4u-mariadb-hotfix vcs [github-repository-url]
```
```bash
composer require --no-update best4u/module-mariadb-hotfix
```
```bash
composer update best4u/module-mariadb-hotfix
```

Then run the following php bin/magento commands from Magento root directory:
```bash
bin/magento maintenance:enable
```
```bash
bin/magento module:enable --clear-static-content Best4u_MariaDbHotfix
```
```bash
bin/magento setup:upgrade
```
```bash
bin/magento setup:di:compile
```
```bash
bin/magento setup:static-content:deploy
```
```bash
bin/magento maintenance:disable
```
```bash
bin/magento cache:flush
```

## Uninstallation
```bash
bin/magento maintenance:enable
```
```bash
bin/magento module:disable Best4u_MariaDbHotfix
```
```bash
composer remove --no-update best4u/module-mariadb-hotfix
```
```bash
composer update best4u/module-mariadb-hotfix
```
```bash
composer config --unset repositories.best4u-mariadb-hotfix
```
```bash
bin/magento setup:upgrade
```
```bash
bin/magento setup:di:compile
```
```bash
bin/magento setup:static-content:deploy
```
```bash
bin/magento maintenance:disable
```
```bash
bin/magento cache:flush
```

## Support

For questions, bug reports, or contributions, please contact us at [magento@best4u.nl](mailto:magento@best4u.nl) or open an issue in the repository.

## License

This module is proprietary. All rights reserved to Best4u Media B.V.