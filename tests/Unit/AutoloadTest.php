<?php
declare(strict_types=1);

namespace Tuqan\Tests\Unit;

use Tuqan\Classes\Config;
use Tuqan\Classes\Auth;
use Tuqan\Classes\Manejador_Base_Datos;
use Tuqan\Tests\TestCase;

final class AutoloadTest extends TestCase
{
    public function testCoreClassesAreLoadedViaPsr4Autoloader(): void
    {
        // These classes should be loadable purely via the PSR-4 autoloader
        // without any manual require_once in this test file.
        $this->assertTrue(class_exists(Config::class));
        $this->assertTrue(class_exists(Auth::class));
        $this->assertTrue(class_exists(Manejador_Base_Datos::class));

        // Basic smoke that they can be used
        Config::initialize();
        $this->assertNotEmpty(Config::$sDbEtc);
    }
}
