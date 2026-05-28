<?php
declare(strict_types=1);

namespace Tuqan\Tests\Unit\Pages;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../../Pages/Logout.php';

use Tuqan\Pages\Logout;
use Tuqan\Tests\TestCase;

final class LogoutTest extends TestCase
{
    public function testLogoutClassExistsAndHasShowPage(): void
    {
        $this->assertTrue(method_exists(Logout::class, 'ShowPage'));
    }

    public function testLogoutClearsKeySessionVariables(): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        // Simulate a logged in state
        $_SESSION['loginempresa'] = 1;
        $_SESSION['usuarioconectado'] = true;
        $_SESSION['nombreUsuario'] = 'admin';
        $_SESSION['db'] = 'qnova';

        $logout = new Logout();
        // We can't easily test the redirect without output buffering, but we can test the side effect
        // by calling the logic indirectly. For now we just ensure it doesn't fatal.
        ob_start();
        try {
            $logout->ShowPage();
        } catch (\Throwable $e) {
            // Expected because of exit/redirect in test context
        }
        ob_end_clean();

        $this->assertArrayNotHasKey('loginempresa', $_SESSION);
        $this->assertArrayNotHasKey('usuarioconectado', $_SESSION);
        $this->assertArrayNotHasKey('nombreUsuario', $_SESSION);
    }
}
