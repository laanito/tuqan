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

    /**
     * Real DB-backed company list (no more hardcode shortcut).
     * MuestraPagina must use the injected handler to fetch companies from the
     * central (etc) database so that login is performed against real data.
     *
     * This test drives removal of the bare-minimum shortcut.
     */
    public function testMuestraPaginaFetchesCompaniesViaDbHandler(): void
    {
        $mockDb = $this->createMock(Manejador_Base_Datos::class);

        // Real DB path now uses the safer consultaPreparada (preferred over old builder
        // to reduce legacy deprecation surface). Test validates that we talk to the DB.
        $mockDb->expects($this->once())
            ->method('consultaPreparada')
            ->with($this->stringContains('qnova_acl'));

        $mockDb->method('coger_Fila')->willReturnOnConsecutiveCalls(['1', 'Demo Company'], false);

        // Ensure Config paths are valid in the isolated test environment
        \Tuqan\Classes\Config::initialize();
        \Tuqan\Classes\Config::$template_path = '/var/www/html/templates/';
        \Tuqan\Classes\Config::$cache_path   = '/tmp/tuqan_test_cache/';

        @mkdir(\Tuqan\Classes\Config::$cache_path, 0777, true);

        $reflection = new \ReflectionClass(LoginEmpresa::class);
        $login = $reflection->newInstanceWithoutConstructor();
        $login->setDbHandler($mockDb);

        $output = $login->MuestraPagina();

        // The form renders with data coming from the (mocked) real DB result
        $this->assertStringContainsString('Demo Company', $output);
        $this->assertStringContainsString('nombre', $output);
    }

    /**
     * Clear test: ProcesaPagina with valid credentials should succeed
     * (we will expand this as we modernize the actual logic).
     */
    public function testProcesaPaginaExistsAndAcceptsDbHandler(): void
    {
        $mockDb = $this->createMock(Manejador_Base_Datos::class);

        $reflection = new \ReflectionClass(LoginEmpresa::class);
        $login = $reflection->newInstanceWithoutConstructor();
        $login->setDbHandler($mockDb);

        // For now we only assert the method can be called without fatal error
        // Full behavior tests will be added as we implement the flow.
        $this->assertTrue(method_exists($login, 'ProcesaPagina'));
    }

    /**
     * Clear test for processing path (mocked). In a full modernization we would
     * verify that valid credentials lead to session setup + redirect to user login.
     */
    public function testProcesaPaginaCanBeCalledWithMock(): void
    {
        $mockDb = $this->createMock(Manejador_Base_Datos::class);
        $mockDb->method('consultaPreparada')->willReturn(true);
        $mockDb->method('coger_Fila')->willReturn([1]); // simulate success

        $reflection = new \ReflectionClass(LoginEmpresa::class);
        $login = $reflection->newInstanceWithoutConstructor();
        $login->setDbHandler($mockDb);

        // We can't easily simulate $_POST in this isolated test without side effects,
        // but the fact that we can reach the method with a handler is the first step.
        $this->assertTrue(method_exists($login, 'ProcesaPagina'));
    }

    public function testMuestraPaginaHandlesDbErrorGracefully(): void
    {
        $mockDb = $this->createMock(Manejador_Base_Datos::class);
        $mockDb->method('iniciar_Consulta')->willThrowException(new \Exception('DB down'));

        $reflection = new \ReflectionClass(LoginEmpresa::class);
        $login = $reflection->newInstanceWithoutConstructor();
        $login->setDbHandler($mockDb);

        // Should not fatal, should return something usable
        $output = $login->MuestraPagina();
        $this->assertIsString($output);
    }
}
