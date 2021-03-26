<?php

namespace App\Tests\unit\core\legacy;

use App\Tests\UnitTester;
use Codeception\Test\Unit;
use Exception;
use App\Currency\LegacyHandler\CurrencyHandler;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class CurrencyHandlerTest
 * @package App\Tests\unit\core\legacy
 */
class CurrencyHandlerTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    /**
     * @var CurrencyHandler
     */
    protected $handler;

    /**
     * @throws Exception
     */
    protected function _before(): void
    {
        $session = new Session(new MockArraySessionStorage('PHPSESSID'));
        $session->start();

        $projectDir = $this->tester->getProjectDir();
        $legacyDir = $this->tester->getLegacyDir();
        $legacySessionName = $this->tester->getLegacySessionName();
        $defaultSessionName = $this->tester->getDefaultSessionName();
        $legacyScope = $this->tester->getLegacyScope();

        $this->handler = new CurrencyHandler(
            $projectDir,
            $legacyDir,
            $legacySessionName,
            $defaultSessionName,
            $legacyScope,
            $session
        );
    }

    // tests

    /**
     * Test retrieval of default currency
     */
    public function testDefaultCurrencyRetrieval(): void
    {
        $currency = $this->handler->getCurrency(null);
        static::assertNotNull($currency);
        static::assertNotEmpty($currency);

        static::assertArrayHasKey('id', $currency);
        static::assertNotEmpty($currency['id']);
        static::assertArrayHasKey('name', $currency);
        static::assertNotEmpty($currency['name']);
        static::assertArrayHasKey('symbol', $currency);
        static::assertNotEmpty($currency['symbol']);
        static::assertArrayHasKey('iso4217', $currency);
        static::assertNotEmpty($currency['iso4217']);
    }

    /**
     * Test retrieval of currency by id
     */
    public function testCurrencyRetrieval(): void
    {
        $currency = $this->handler->getCurrency(-99);
        static::assertNotNull($currency);
        static::assertNotEmpty($currency);

        static::assertArrayHasKey('id', $currency);
        static::assertNotEmpty($currency['id']);
        static::assertEquals($currency['id'], -99);
        static::assertArrayHasKey('name', $currency);
        static::assertNotEmpty($currency['name']);
        static::assertArrayHasKey('symbol', $currency);
        static::assertNotEmpty($currency['symbol']);
        static::assertArrayHasKey('iso4217', $currency);
        static::assertNotEmpty($currency['iso4217']);
    }
}
