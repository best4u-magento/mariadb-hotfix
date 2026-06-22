<?php
/**
 * Copyright © Best4u Media B.V. All rights reserved.
 */
declare(strict_types=1);

namespace Best4u\MariaDbHotfix\Plugin\Framework\Setup\Declaration\Schema\Db\DefinitionAggregator;

use Magento\Framework\Setup\Declaration\Schema\Db\DefinitionAggregator;
use Magento\Framework\DB\Adapter\SqlVersionProvider;

class DefaultValue
{

    /**
     * SQL version provider instance
     *
     * @var null|SqlVersionProvider
     */
    private $sqlVersionProvider;

    /**
     * Initialize dependencies
     *
     * @param SqlVersionProvider $sqlVersionProvider
     */
    public function __construct(SqlVersionProvider $sqlVersionProvider)
    {
        $this->sqlVersionProvider = $sqlVersionProvider;
    }

    /**
     * Update default value
     *
     * @param DefinitionAggregator $subject
     * @param array $data
     * @return null|array
     */
    public function beforeFromDefinition(DefinitionAggregator $subject, array $data): ?array
    {
        if (array_key_exists('default', $data) && !method_exists($this->sqlVersionProvider, 'isMariaDbEngine')) {
            $sqlVersion = $this->sqlVersionProvider->getSqlVersion();

            if (preg_match('/^(11|12)\./', $sqlVersion) && 'NULL' === $data['default']) {
                $data['default'] = null;

                return [
                    $data
                ];
            }
        }

        return null;
    }
}
