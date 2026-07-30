<?php
namespace Tuqan\Pages\Proveedores;

/**
 * Supplier homologation status helpers (Stage 9.40).
 */
class HomologacionHelper
{
    /**
     * Homologado if has fecha_homologacion and no later deshomologacion.
     */
    public static function isHomologado(?string $fechaHomologacion, ?string $fechaDeshomologacion): bool
    {
        if ($fechaHomologacion === null || $fechaHomologacion === '') {
            return false;
        }
        if ($fechaDeshomologacion === null || $fechaDeshomologacion === '') {
            return true;
        }
        return substr((string)$fechaHomologacion, 0, 10) > substr((string)$fechaDeshomologacion, 0, 10);
    }

    public static function label(?string $fechaHomologacion, ?string $fechaDeshomologacion): string
    {
        return self::isHomologado($fechaHomologacion, $fechaDeshomologacion)
            ? 'Homologado'
            : 'No homologado';
    }

    public static function badgeClass(?string $fechaHomologacion, ?string $fechaDeshomologacion): string
    {
        return self::isHomologado($fechaHomologacion, $fechaDeshomologacion)
            ? 'label-success'
            : 'label-default';
    }
}
