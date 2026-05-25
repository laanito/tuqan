<?php
declare(strict_types=1);

namespace Tuqan\Tests\Unit\Classes;

use PHPUnit\Framework\TestCase;
use Tuqan\Classes\Config;

final class ConfigTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset the singleton-like initialized flag before each test
        $reflection = new \ReflectionClass(Config::class);
        $property = $reflection->getProperty('initialized');
        $property->setAccessible(true);
        $property->setValue(null, false);
    }

    public function testInitializeSetsBasicDefaults(): void
    {
        Config::initialize();

        $this->assertNotEmpty(Config::$sDbEtc);
        $this->assertSame(5432, Config::$iPuertoEtc);
        $this->assertIsArray(Config::$aCharset);
    }

    public function testInitializeReadsFromEnvironmentVariables(): void
    {
        putenv('DB_HOST=testhost.example.com');
        putenv('DB_PORT=1234');
        putenv('DB_USER=testuser');
        putenv('DB_PASS=testpass123');
        putenv('DB_NAME=testdb');
        putenv('APP_LANG_ID=2');
        putenv('APP_LANG_INITIAL=catalan');

        Config::initialize();

        $this->assertSame('testhost.example.com', Config::$sServidorEtc);
        $this->assertSame(1234, Config::$iPuertoEtc);
        $this->assertSame('testuser', Config::$sLoginEtc);
        $this->assertSame('testpass123', Config::$sPassEtc);
        $this->assertSame('testdb', Config::$sDbEtc);
        $this->assertSame('2', Config::$sIdioma);
        $this->assertSame('catalan', Config::$sIdiomaInicial);

        // Clean up environment
        putenv('DB_HOST');
        putenv('DB_PORT');
        putenv('DB_USER');
        putenv('DB_PASS');
        putenv('DB_NAME');
        putenv('APP_LANG_ID');
        putenv('APP_LANG_INITIAL');
    }
}
