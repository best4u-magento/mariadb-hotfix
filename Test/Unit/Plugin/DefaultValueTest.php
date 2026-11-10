<?php
/**
 * Copyright © Best4u Media B.V. All rights reserved.
 */
declare(strict_types=1);

namespace Best4u\MariaDbHotfix\Test\Unit\Plugin;

use Best4u\MariaDbHotfix\Plugin\Framework\Setup\Declaration\Schema\Db\DefinitionAggregator\DefaultValue;
use Magento\Framework\DB\Adapter\SqlVersionProvider;
use Magento\Framework\Setup\Declaration\Schema\Db\DefinitionAggregator;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for the MariaDB 11/12 "NULL" default fix
 */
class DefaultValueTest extends TestCase
{
    /**
     * Data provider: SQL version, handled by Magento, default value => expected plugin result
     *
     * @return array<string,array{0: string, 1: bool, 2: mixed, 3: array<int,array<string,mixed>>|null}>
     */
    public static function defaultValueDataProvider(): array
    {
        $fixed = [['name' => 'col', 'default' => null]];

        return [
            'MariaDB 11.4, old Magento: fixed' => ['11.4.', false, 'NULL', $fixed],
            'MariaDB 12.2, old Magento: fixed' => ['12.2.', false, 'NULL', $fixed],
            'MariaDB 11.4, Magento handles it' => ['11.4.', true, 'NULL', null],
            'MariaDB 10.6: Magento handles it' => ['10.6.', false, 'NULL', null],
            'MySQL 8.0: not MariaDB' => ['8.0.', false, 'NULL', null],
            'MariaDB 11.4, other default' => ['11.4.', false, '0', null],
            'MariaDB 11.4, quoted NULL string' => ['11.4.', false, "'NULL'", null],
        ];
    }

    /**
     * @dataProvider defaultValueDataProvider
     * @param string $sqlVersion
     * @param bool $handledByMagento
     * @param mixed $default
     * @param array<int,array<string,mixed>>|null $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('defaultValueDataProvider')]
    public function testBeforeFromDefinition(string $sqlVersion, bool $handledByMagento, $default, ?array $expected): void
    {
        $result = $this->createPlugin($sqlVersion, $handledByMagento)->beforeFromDefinition(
            $this->createStub(DefinitionAggregator::class),
            ['name' => 'col', 'default' => $default]
        );

        $this->assertSame($expected, $result);
    }

    /**
     * Column definitions without default are not changed
     *
     * @return void
     */
    public function testDefinitionWithoutDefault(): void
    {
        $this->assertNull(
            $this->createPlugin('11.4.', false)->beforeFromDefinition(
                $this->createStub(DefinitionAggregator::class),
                ['name' => 'col']
            )
        );
    }

    /**
     * Create the plugin for the given SQL version and Magento capability
     *
     * @param string $sqlVersion
     * @param bool $handledByMagento
     * @return DefaultValue
     */
    private function createPlugin(string $sqlVersion, bool $handledByMagento): DefaultValue
    {
        $provider = $this->createStub(SqlVersionProvider::class);
        $provider->method('getSqlVersion')->willReturn($sqlVersion);

        return new class($provider, $handledByMagento) extends DefaultValue {
            /**
             * @param SqlVersionProvider $provider
             * @param bool $handled
             */
            public function __construct(SqlVersionProvider $provider, private readonly bool $handled)
            {
                parent::__construct($provider);
            }

            /**
             * @inheritdoc
             */
            protected function isHandledByMagento(): bool
            {
                return $this->handled;
            }
        };
    }
}
