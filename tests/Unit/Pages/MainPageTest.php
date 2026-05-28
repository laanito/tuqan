<?php
declare(strict_types=1);

namespace Tuqan\Tests\Unit\Pages;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../../Pages/MainPage.php';

use Tuqan\Pages\MainPage;
use Tuqan\Tests\TestCase;

final class MainPageTest extends TestCase
{
    public function testMainPageClassExistsAndHasShowPage(): void
    {
        $this->assertTrue(method_exists(MainPage::class, 'ShowPage'));
    }

    public function testMainPageRendersWithoutFatalWithMinimalSession(): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        // Simulate post-login state
        $_SESSION['nombreUsuario'] = 'admin';
        $_SESSION['idioma'] = '1';
        $_SESSION['admin'] = true;
        $_SESSION['perfil'] = '0';

        $page = new MainPage();
        $output = $page->ShowPage();

        $this->assertIsString($output);
        $this->assertStringContainsString('Tuqan', $output);
        $this->assertStringContainsString('admin', $output);
    }
}
