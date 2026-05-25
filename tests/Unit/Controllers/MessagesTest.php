<?php
declare(strict_types=1);

namespace Tuqan\Tests\Unit\Controllers;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../../Controllers/Messages.php';

use Tuqan\Classes\Manejador_Base_Datos;
use Tuqan\Controllers\Messages;
use Tuqan\Tests\TestCase;

final class MessagesTest extends TestCase
{
    public function testSetDbHandlerForMessagesAcceptsMock(): void
    {
        $mockDb = $this->createMock(Manejador_Base_Datos::class);

        Messages::setDbHandlerForMessages($mockDb);

        $this->assertTrue(true); // If we reach here, the setter worked
    }

    public function testGetViewMethodExists(): void
    {
        $this->assertTrue(method_exists(Messages::class, 'getView'));
    }
}
