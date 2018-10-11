<?php
/**
 * Class to retrieve action buttons related to current action
 */

namespace Tuqan\Classes;


class Botones
{

    /**
     * @var Manejador_Base_Datos
     */
    private static $oDb;

    /*
     * @var boolean
     */
    private static $initialized = false;

    /**
     * Initialize variables
     */
    static function initialize(){
        if(!self::$initialized) {
            self::$oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
            self::$initialized = true;
        }
    }

    /**
     * @param $action
     * @return array
     */
    static function getButtons($action) {
        self::initialize();
        self::$oDb->iniciar_Consulta('SELECT');
        self::$oDb->construir_Campos(array('botones_idiomas.valor', 'botones.accion', 'tipo_botones.nombre'));
        self::$oDb->construir_Tablas(array('botones', 'menu_nuevo', 'botones_idiomas', 'idiomas', 'tipo_botones'));
        self::$oDb->construir_Where(array('menu_nuevo.id=botones.menu', 'menu_nuevo.accion=\'' . $action . '\'',
            'botones_idiomas.boton=botones.id', "botones_idiomas.idioma_id=idiomas.id", "idiomas.id='" . $_SESSION['idiomaid'] . "'",
            'tipo_botones.id=botones.tipo_botones'));
        self::$oDb->consulta();
        $aBotones = array();
        while ($aIterador = self::$oDb->coger_Fila()) {
            $aBotones[] = array($aIterador[0], $aIterador[1], $aIterador[2]);
        }
        return $aBotones;
    }
}
