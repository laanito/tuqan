<?php

namespace Tuqan\Pages;

/**
 * Simple logout handler for the minimal working app.
 * Clears relevant session keys and redirects back to company login.
 */
class Logout
{
    public function ShowPage()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        // Clear all Tuqan login-related session keys
        $keysToClear = [
            'loginempresa', 'usuarioconectado', 'admin', 'perfil',
            'nombreUsuario', 'idioma', 'idiomaid', 'empresa',
            'db', 'login', 'pass', 'conectado'
        ];

        foreach ($keysToClear as $key) {
            unset($_SESSION[$key]);
        }

        // Optional: completely destroy session if we want a full reset
        // session_destroy();
        // session_start(); // if we want to keep using $_SESSION after

        // Redirect to company login form
        if (!headers_sent()) {
            header('Location: /login/empresa/');
        }
        exit();
    }
}
