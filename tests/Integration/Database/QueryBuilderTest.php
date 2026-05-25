<?php
declare(strict_types=1);

namespace Tuqan\Tests\Integration\Database;

use PHPUnit\Framework\TestCase;
use Tuqan\Classes\Manejador_Base_Datos;

/**
 * Basic smoke test for the legacy query builder.
 * This will likely need adjustments as we improve the testing setup.
 */
final class QueryBuilderTest extends TestCase
{
    public function testCanInstantiateQueryBuilder(): void
    {
        // We are not yet connecting to a real test DB in Stage 2
        $this->assertTrue(class_exists(Manejador_Base_Datos::class));
    }
}
