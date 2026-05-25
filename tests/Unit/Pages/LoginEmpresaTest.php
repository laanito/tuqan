<?php
declare(strict_types=1);

namespace Tuqan\Tests\Unit\Pages;

// Self-contained requires for this test file
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../../Pages/LoginEmpresa.php';

use Tuqan\Pages\LoginEmpresa;
use Tuqan\Classes\Manejador_Base_Datos;
use Tuqan\Tests\TestCase;

final class LoginEmpresaTest extends TestCase
{
    public function testSetDbHandlerAcceptsMock(): void
    {
        $mockDb = $this->createMock(Manejador_Base_Datos::class);

        // Use reflection to bypass the constructor (which has many legacy dependencies)
        $reflection = new \ReflectionClass(LoginEmpresa::class);
        $login = $reflection->newInstanceWithoutConstructor();

        $login->setDbHandler($mockDb);

        // If we reach here, the setter accepted the mock successfully
        $this->assertTrue(true);
    }

    public function testMuestraPaginaMethodExists(): void
    {
        $this->assertTrue(method_exists(LoginEmpresa::class, 'MuestraPagina'));
    }

    public function testProcesaPaginaMethodExists(): void
    {
        $this->assertTrue(method_exists(LoginEmpresa::class, 'ProcesaPagina'));
    }
}
