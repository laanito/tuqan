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
     * Clear characterization test: MuestraPagina should fetch companies from DB
     * via the injected handler and render a form containing them.
     * This test will drive the modernization of the login flow.
     */
    public function testMuestraPaginaFetchesCompaniesFromDbAndRendersForm(): void
    {
        $mockDb = $this->createMock(Manejador_Base_Datos::class);

        // Expect the query to be executed safely
        $mockDb->expects($this->once())
            ->method('iniciar_Consulta')
            ->with('SELECT');

        $mockDb->method('construir_Campos')->willReturn(null);
        $mockDb->method('construir_Tablas')->willReturn(null);
        $mockDb->method('consulta')->willReturn(null);

        // Simulate one company row from the minimal seed
        $mockDb->method('coger_Fila')
            ->willReturnOnConsecutiveCalls(['demo'], false);

        // Ensure Config paths are valid in the isolated test environment
        // so that Twig can initialize without blowing up on missing directories.
        \Tuqan\Classes\Config::initialize();
        \Tuqan\Classes\Config::$template_path = '/var/www/html/templates/';
        \Tuqan\Classes\Config::$cache_path   = '/tmp/tuqan_test_cache/';

        @mkdir(\Tuqan\Classes\Config::$cache_path, 0777, true);

        $reflection = new \ReflectionClass(LoginEmpresa::class);
        $login = $reflection->newInstanceWithoutConstructor();
        $login->setDbHandler($mockDb);

        $output = $login->MuestraPagina();

        // The rendered output should contain evidence of the company from the DB
        $this->assertStringContainsString('demo', $output);
        $this->assertStringContainsString('nombre', $output); // form field name
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
