<?php
declare(strict_types=1);

namespace Tuqan\Tests\Integration\Database;

use PHPUnit\Framework\TestCase;
use Tuqan\Classes\Manejador_Base_Datos;

final class QueryBuilderTest extends TestCase
{
    public function testCanInstantiateManejador(): void
    {
        // We don't connect to DB yet in early Stage 2
        $this->assertTrue(class_exists(Manejador_Base_Datos::class));
    }

    public function testConsultaPreparadaMethodExists(): void
    {
        $reflection = new \ReflectionClass(Manejador_Base_Datos::class);
        $this->assertTrue($reflection->hasMethod('consultaPreparada'));
    }

    public function testConsultaPreparadaUsesPrepareAndExecute(): void
    {
        $mockPdoStatement = $this->createMock(\PDOStatement::class);
        $mockPdoStatement->expects($this->once())
            ->method('execute')
            ->with(['Test User'])
            ->willReturn(true);

        $db = $this->getMockBuilder(Manejador_Base_Datos::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['prepare'])
            ->getMock();

        $db->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO users (name) VALUES (?)')
            ->willReturn($mockPdoStatement);

        $result = $db->consultaPreparada('INSERT INTO users (name) VALUES (?)', ['Test User']);

        $this->assertTrue($result);
    }

    public function testConsultaPreparadaCanBeCalled(): void
    {
        // Lightweight integration test using SQLite
        $db = new Manejador_Base_Datos('', '', ':memory:', '', 0, 'sqlite');

        $db->exec("DROP TABLE IF EXISTS test_users");
        $db->exec("CREATE TABLE test_users (id INTEGER PRIMARY KEY, name TEXT)");

        $sql = "INSERT INTO test_users (name) VALUES (?)";
        $result = $db->consultaPreparada($sql, ['Test User']);

        $this->assertTrue($result);
    }
}
