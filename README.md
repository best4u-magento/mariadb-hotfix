# Best4u MariaDB Hotfix module for Magento 2

Adds support for **MariaDB 11.4, 11.8 and 12.2–12.3** to Magento 2.4.4 – 2.4.8, which do not (fully) support these MariaDB versions.

**Magento 2.4.9 and later support these MariaDB versions natively: the module is not needed there.** It can stay installed during an upgrade to 2.4.9 (it does nothing there), but should be removed afterwards.

## Table of Contents

- [Requirements](#requirements)
- [What the module does](#what-the-module-does)
- [Which Magento versions need it](#which-magento-versions-need-it)
- [Installation](#installation)
- [Uninstallation](#uninstallation)
- [Support](#support)
- [License](#license)

## Requirements

- Magento Open Source / Adobe Commerce 2.4.4 – 2.4.8 (installable on 2.4.9, where it has no effect)
- PHP 8.1, 8.2, 8.3, 8.4 or 8.5 (within the range supported by your Magento version)
- Composer 2.2 or later

## What the module does

1. **Accepted database versions** – adds these patterns to the database versions accepted by Magento (`Magento\Framework\DB\Adapter\SqlVersionProvider`); without them, `bin/magento setup:upgrade` and other commands stop with "Current version of RDBMS is not supported":
   - MariaDB 10.2 – 10.6, 10.11
   - MariaDB 11.4, 11.8
   - MariaDB 12.2, 12.3
   - MySQL 5.7, 8.0, 8.4

   The patterns are only added; patterns of your Magento version are never removed.

   > **Note:** MySQL 8.4 is supported by Magento itself only from 2.4.8. On older versions the module allows it, but it is not tested by Adobe.

2. **NULL column defaults** – MariaDB reports a `NULL` column default as the text `NULL`. Magento versions without `SqlVersionProvider::isMariaDbEngine()` only recognize MariaDB 10.x, so on MariaDB 11/12 the declarative schema sees a difference for every nullable column with a `NULL` default (on every `setup:upgrade`). The module converts this value to a real `NULL`. On Magento versions that handle this themselves, this fix switches itself off.

## Which Magento versions need it

| Magento version | Without the module | Module needed for |
|---|---|---|
| 2.4.4 – 2.4.7 (patch releases up to 2.4.7-p9) | MariaDB 10.x only; NULL defaults not handled for MariaDB 11+ | MariaDB 11.4, 11.8, 12.x |
| 2.4.7-p10 | MariaDB 10.2 – 10.11, 11.4, 11.8 | MariaDB 12.x |
| 2.4.8 – 2.4.8-p4 | MariaDB 10.2 – 10.6, 11.4 | MariaDB 11.8, 12.x |
| 2.4.8-p5 | MariaDB 10.2 – 10.6, 11.4, 11.8 | MariaDB 12.x |
| 2.4.9 and later | MariaDB 10.2 – 10.6, 10.11, 11.4, 11.8, 12.2, 12.3 | – (not needed) |

## Installation

```bash
composer config repositories.best4u-mariadb-hotfix vcs https://github.com/best4u-magento/mariadb-hotfix.git
composer require best4u/module-mariadb-hotfix
bin/magento module:enable Best4u_MariaDbHotfix
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

The module has no frontend or admin parts, so no static content deployment is needed.

> If `bin/magento` already stops with "Current version of RDBMS is not supported" before the module is enabled, enable it by adding `'Best4u_MariaDbHotfix' => 1,` to the `modules` list in `app/etc/config.php` and run `bin/magento setup:upgrade`.

## Uninstallation

Before removing the module, make sure your Magento version supports your database version (see the table above), e.g. after upgrading to Magento 2.4.9:

```bash
bin/magento module:disable Best4u_MariaDbHotfix
composer remove best4u/module-mariadb-hotfix
composer config --unset repositories.best4u-mariadb-hotfix
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

## Support

For questions, bug reports, or contributions, please contact us at [magento@best4u.nl](mailto:magento@best4u.nl) or open an issue in the repository.

## License

This module is proprietary. All rights reserved to Best4u Media B.V.
