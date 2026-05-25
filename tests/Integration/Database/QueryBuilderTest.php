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
}
