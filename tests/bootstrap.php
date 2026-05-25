<?php
declare(strict_types=1);

/**
 * Bootstrap file for PHPUnit tests.
 *
 * For Stage 2 we keep this minimal. Later stages will expand it
 * with proper test database handling, session mocking, etc.
 */

require __DIR__ . '/../vendor/autoload.php';

// Temporary requires for Stage 2 (until proper autoloading in Stage 4)
require __DIR__ . '/../Classes/Config.php';
require __DIR__ . '/../Classes/Manejador_Base_Datos.class.php';
require __DIR__ . '/../Classes/Auth.php';
require __DIR__ . '/../Classes/User.php';

// Basic session handling (many legacy classes expect $_SESSION to exist)
if (!isset($_SESSION)) {
    session_start();
}

// TODO (Stage 3+): Load environment variables and override Config for testing
// TODO: Initialize a test database connection
