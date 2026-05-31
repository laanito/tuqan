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

        // Simulate post-login state with extended user info (Stage 8.4+)
        $_SESSION['nombreUsuario'] = 'Administrador Demo';
        $_SESSION['idioma'] = '1';
        $_SESSION['admin'] = true;
        $_SESSION['perfil'] = '0';
        $_SESSION['empresa'] = 'Demo Company';
        $_SESSION['usuario_nombre'] = 'Administrador';
        $_SESSION['usuario_apellido'] = 'Demo';
        $_SESSION['usuario_email'] = 'admin@demo.local';

        // Ensure Config paths are set for Twig (PHPUnit CLI has no DOCUMENT_ROOT)
        $_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/../../..';
        \Tuqan\Classes\Config::initialize();

        $page = new MainPage();
        $output = $page->ShowPage();

        $this->assertIsString($output);
        $this->assertStringContainsString('Tuqan', $output);
        $this->assertStringContainsString('admin', $output);
        $this->assertStringContainsString('Demo Company', $output); // new CompanyName variable
    }

    /**
     * Menu action resolver must turn legacy colon-routes into clean Phroute paths.
     */
    public function testResolveLegacyActionConvertsColonsToSlashes(): void
    {
        // Use reflection to reach the private method for unit testing the pure logic
        $page = new MainPage();
        $ref = new \ReflectionClass($page);
        $method = $ref->getMethod('resolveLegacyAction');
        $method->setAccessible(true);

        $this->assertSame('/administracion/usuarios/listado', $method->invoke($page, 'administracion:usuarios:listado'));
        $this->assertSame('/main/', $method->invoke($page, '/main/'));
        $this->assertSame('#', $method->invoke($page, ''));
        $this->assertSame('#', $method->invoke($page, '#'));
    }

    /**
     * When there is no valid session for menu building, crea_Menu_Superior must return
     * a safe non-crashing fallback instead of querying the DB.
     */
    public function testCreaMenuSuperiorReturnsSafeFallbackWithoutSession(): void
    {
        // Ensure clean session state for this test
        if (isset($_SESSION)) {
            $keys = ['idioma', 'loginempresa', 'db_host'];
            foreach ($keys as $k) {
                unset($_SESSION[$k]);
            }
        }

        $page = new MainPage();
        $html = $page->crea_Menu_Superior();

        $this->assertIsString($html);
        $this->assertStringContainsString('menu requires login', $html);
    }
}
