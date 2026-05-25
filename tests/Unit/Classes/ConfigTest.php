<?php
declare(strict_types=1);

namespace Tuqan\Tests\Unit\Classes;

use PHPUnit\Framework\TestCase;
use Tuqan\Classes\Config;

final class ConfigTest extends TestCase
{
    public function testInitializeSetsBasicDefaults(): void
    {
        Config::initialize();

        $this->assertNotEmpty(Config::$sDbEtc);
        $this->assertSame(5432, Config::$iPuertoEtc);
        $this->assertIsArray(Config::$aCharset);
    }
}
