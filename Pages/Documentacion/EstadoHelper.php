<?php
namespace Tuqan\Pages\Documentacion;

/**
 * Document estado codes (legacy constantes.inc.php: iVigor, iBorrador, …).
 * Kept local to Documentación so Catalog stays generic.
 */
class EstadoHelper
{
    public static function map(): array
    {
        return [
            1 => 'En vigor',
            2 => 'Borrador',
            3 => 'Pend. revisión',
            4 => 'Revisado',
            5 => 'Pend. aprobación',
            6 => 'Histórico',
        ];
    }

    public static function label($estado): string
    {
        if ($estado === null || $estado === '') {
            return '—';
        }
        $map = self::map();
        $key = (int)$estado;
        return $map[$key] ?? ('Estado ' . $key);
    }

    public static function options(): array
    {
        $opts = [];
        foreach (self::map() as $id => $nombre) {
            $opts[] = ['id' => $id, 'nombre' => $nombre];
        }
        return $opts;
    }

    /** Bootstrap label class for badges */
    public static function badgeClass($estado): string
    {
        switch ((int)$estado) {
            case 1: return 'label-success';   // En vigor
            case 2: return 'label-default';   // Borrador
            case 3: return 'label-warning';   // Pend. revisión
            case 4: return 'label-info';      // Revisado
            case 5: return 'label-primary';   // Pend. aprobación
            case 6: return 'label-default';   // Histórico
            default: return 'label-default';
        }
    }
}
