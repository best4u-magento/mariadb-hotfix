<?php
/**
 * Copyright © Best4u Media B.V. All rights reserved.
 */
declare(strict_types=1);

namespace Best4u\MariaDbHotfix\Plugin\Framework\Setup\Declaration\Schema\Db\DefinitionAggregator;

use Magento\Framework\DB\Adapter\SqlVersionProvider;
use Magento\Framework\Setup\Declaration\Schema\Db\DefinitionAggregator;

/**
 * Fixes the "NULL" column default reported by MariaDB 11 and 12 on Magento versions without native support
 *
 * MariaDB reports a NULL default as the string "NULL". Magento versions before isMariaDbEngine() was introduced only
 * recognize MariaDB 10.x, so on MariaDB 11/12 the declarative schema sees a difference for every nullable column
 * with a NULL default. Newer Magento versions (2.4.7-p10, 2.4.8 and later) handle this themselves; the plugin then
 * does nothing.
 */
class DefaultValue
{
    /**
     * @param SqlVersionProvider $sqlVersionProvider
     */
    public function __construct(
        private readonly SqlVersionProvider $sqlVersionProvider
    ) {
    }

    /**
     * Convert the "NULL" default reported by MariaDB 11/12 to a real NULL
     *
     * @param DefinitionAggregator $subject
     * @param array<string,mixed> $data
     * @return array<int,array<string,mixed>>|null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeFromDefinition(DefinitionAggregator $subject, array $data): ?array
    {
        if (!array_key_exists('default', $data) || $data['default'] !== 'NULL' || $this->isHandledByMagento()) {
            return null;
        }

        if (!preg_match('/^(11|12)\./', $this->sqlVersionProvider->getSqlVersion())) {
            return null;
        }

        $data['default'] = null;

        return [$data];
    }

    /**
     * Check whether this Magento version detects MariaDB 11+ itself (SqlVersionProvider::isMariaDbEngine())
     *
     * @return bool
     */
    protected function isHandledByMagento(): bool
    {
        return method_exists($this->sqlVersionProvider, 'isMariaDbEngine');
    }
}
