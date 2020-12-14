<?php

namespace App\Tests\unit\core\legacy\Statistics;

use App\Legacy\ModuleNameMapperHandler;
use App\Tests\_mock\Mock\core\legacy\Statistics\SubPanelQuotesTotalMock;
use App\Tests\UnitTester;
use Codeception\Test\Unit;
use Exception;

/**
 * Class SubPanelQuotesTotalTest
 * @package App\Tests
 */
class SubPanelQuotesTotalTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    /**
     * @var SubPanelQuotesTotalMock
     */
    private $handler;

    /**
     * @throws Exception
     */
    protected function _before(): void
    {
        $projectDir = $this->tester->getProjectDir();
        $legacyDir = $this->tester->getLegacyDir();
        $legacySessionName = $this->tester->getLegacySessionName();
        $defaultSessionName = $this->tester->getDefaultSessionName();

        $legacyScope = $this->tester->getLegacyScope();

        $moduleNameMapper = new ModuleNameMapperHandler(
            $projectDir,
            $legacyDir,
            $legacySessionName,
            $defaultSessionName,
            $legacyScope
        );


        $this->handler = new SubPanelQuotesTotalMock(
            $projectDir,
            $legacyDir,
            $legacySessionName,
            $defaultSessionName,
            $legacyScope,
            $moduleNameMapper
        );
    }

    /**
     * Test Unsupported context module
     * @throws Exception
     */
    public function testUnsupportedContextModule(): void
    {
        $this->handler->reset();

        $result = $this->handler->getData(
            [
                'context' => [
                    'id' => '12345',
                ]
            ]
        );

        static::assertNotNull($result);
        static::assertNotNull($result->getData());
        static::assertNotNull($result->getMetadata());
        static::assertIsArray($result->getData());
        static::assertIsArray($result->getMetadata());
        static::assertEquals('quotes', $result->getId());
        static::assertArrayHasKey('type', $result->getMetadata());
        static::assertEquals('single-value-statistic', $result->getMetadata()['type']);
        static::assertArrayHasKey('dataType', $result->getMetadata());
        static::assertEquals('varchar', $result->getMetadata()['dataType']);
        static::assertArrayHasKey('value', $result->getData());
        static::assertEquals('-', $result->getData()['value']);
    }

    /**
     * Test get Total response
     * @throws Exception
     */
    public function testGetTotalResponse(): void
    {
        $this->handler->reset();

        $rows = [
            [
                'expiration' => '2012-10-10',
            ],
        ];
        $this->handler->setMockQueryResult($rows);

        $result = $this->handler->getData(
            [
                'key' => 'quotes',
                'context' => [
                    'module' => 'contacts',
                    'id' => '12345',
                ],
                'params' => [
                    'subpanel' => 'subpanel_name'
                ]
            ]
        );

        static::assertNotNull($result);
        static::assertNotNull($result->getData());
        static::assertNotNull($result->getMetadata());
        static::assertIsArray($result->getData());
        static::assertIsArray($result->getMetadata());
        static::assertArrayHasKey('value', $result->getData());
        static::assertEquals('2012-10-10', $result->getData()['value']);
        static::assertEquals('quotes', $result->getId());
        static::assertArrayHasKey('type', $result->getMetadata());
        static::assertEquals('single-value-statistic', $result->getMetadata()['type']);
        static::assertArrayHasKey('dataType', $result->getMetadata());
        static::assertEquals('date', $result->getMetadata()['dataType']);
    }
}
