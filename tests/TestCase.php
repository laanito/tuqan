<?php
declare(strict_types=1);

namespace Tuqan\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Tuqan\Classes\Manejador_Base_Datos;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates a mock for Manejador_Base_Datos.
     *
     * This is the recommended way to test classes that depend on the DB layer
     * during Stage 3 and early Stage 4, before we have a proper test database harness.
     *
     * @param array $methodsToMock Optional list of methods to mock. Defaults to common ones.
     * @return Manejador_Base_Datos|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockDbHandler(array $methodsToMock = null): Manejador_Base_Datos
    {
        $methods = $methodsToMock ?? [
            'consultaPreparada',
            'coger_Fila',
            'desconexion',
            'comienza_transaccion',
            'termina_transaccion',
            'iniciar_Consulta',
            'construir_Campos',
            'construir_Tablas',
            'construir_Where',
            'consulta',
        ];

        return $this->createMock(Manejador_Base_Datos::class, $methods);
    }

    /**
     * Helper to create a mock that simulates a successful single-row fetch.
     */
    protected function createMockDbHandlerWithRow(array $row): Manejador_Base_Datos
    {
        $mock = $this->createMockDbHandler(['consultaPreparada', 'coger_Fila', 'desconexion']);

        $mock->method('consultaPreparada')->willReturn(true);
        $mock->method('coger_Fila')->willReturn($row);
        $mock->method('desconexion')->willReturn(null);

        return $mock;
    }
}
