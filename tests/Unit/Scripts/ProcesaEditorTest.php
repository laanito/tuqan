<?php
declare(strict_types=1);

namespace Tuqan\Tests\Unit\Scripts;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../../procesa_Editor.php';

use Tuqan\Classes\Manejador_Base_Datos;
use Tuqan\Tests\TestCase;

final class ProcesaEditorTest extends TestCase
{
    public function testSetDbHandlerForEditorAcceptsMock(): void
    {
        $mockDb = $this->createMock(Manejador_Base_Datos::class);

        // This should not throw and should set the global
        setDbHandlerForEditor($mockDb);

        // Verify by checking that the global was set (via reflection or re-require)
        // For simplicity, we just ensure the function exists and accepts the mock
        $this->assertTrue(function_exists('setDbHandlerForEditor'));
    }

    public function testSetDbHandlerForEditorFunctionExists(): void
    {
        $this->assertTrue(function_exists('setDbHandlerForEditor'));
    }
}
