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

    public function testConsultaPreparadaCanBeCalled(): void
    {
        // Use an in-memory SQLite connection for isolation
        // Note: Constructor expects (login, pass, db, host, port, type)
        $db = new Manejador_Base_Datos('', '', ':memory:', '', 0, 'sqlite');

        // Create a simple test table (idempotent for test runs)
        $db->exec("DROP TABLE IF EXISTS test_users");
        $db->exec("CREATE TABLE test_users (id INTEGER PRIMARY KEY, name TEXT)");

        // Use the new prepared statement method
        $sql = "INSERT INTO test_users (name) VALUES (?)";
        $result = $db->consultaPreparada($sql, ['Test User']);

        $this->assertTrue($result);

        // Verify the insert worked (basic check)
        $this->assertTrue($db->consultaPreparada("SELECT COUNT(*) FROM test_users", []));

        $row = $db->coger_Fila(\PDO::FETCH_NUM);
        $this->assertNotNull($row);
        $this->assertGreaterThan(0, (int)$row[0]);
    }
}
