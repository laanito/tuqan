<?php
declare(strict_types=1);

namespace Tuqan\Tests\Unit\Classes;

use Tuqan\Classes\Auth;
use Tuqan\Classes\Manejador_Base_Datos;
use Tuqan\Tests\TestCase;

final class AuthTest extends TestCase
{
    public function testFetchUserByIdCallsConsultaPreparada(): void
    {
        $mockDb = $this->createMock(Manejador_Base_Datos::class);

        // Expect the safer method to be called with a prepared statement
        $mockDb->expects($this->once())
            ->method('consultaPreparada')
            ->with(
                $this->stringContains('SELECT id, login, perfil'),
                $this->equalTo([42])
            )
            ->willReturn(true);

        $mockDb->method('coger_Fila')->willReturn([42, 'testuser', 5, 10, 'pass', 1]);
        $mockDb->method('desconexion')->willReturn(null);

        $auth = $this->getMockBuilder(Auth::class)
            ->onlyMethods(['getRoleById', 'updateSessionData'])
            ->getMock();

        $auth->expects($this->once())->method('getRoleById')->willReturn([]);
        $auth->expects($this->once())->method('updateSessionData');

        // Use the new setter introduced for testability in Stage 3
        $auth->setDbHandler($mockDb);

        // This will now use the injected mock and trigger the expectation
        $auth->fetchUserById(42);
    }
}
