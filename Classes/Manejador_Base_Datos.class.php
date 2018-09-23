<?php
namespace Tuqan\Classes;
/**
* LICENSE see LICENSE.md file
 *
 * Es nuestro fichero que contiene la clase con la que conectamos, enviamos peticiones a la base
 * de datos y controlamos los posibles errores
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 *
 */



/**
 * Class Manejador_Base_Datos
 */
class Manejador_Base_Datos extends \DB
{

    // Attributos

    /**
     *    Este sera el login con el que conectar a la base de datos
     * @access private
     * @var string
     */

    private $sUser;

    /**
     *    Este sera el password con el que conectar a la base de datos
     * @access private
     * @var string
     */

    private $sPasswd;

    /**
     *    Este es el servidor donde se encuentra las bases de datos
     * @access private
     * @var string
     */

    private $sHost;


    /**
     *    Este es el puerto mediante el cual conectar a la base de datos
     * @access private
     * @var string
     */

    private $sPort;

    /**
     *    Este es la base de datos que usaremos
     * @access private
     * @var string
     */

    private $sTipo_Bd;

    /**
     *    Esta es el nombre de la base de datos relacionada
     * @access private
     * @var string
     */

    private $sDb;

    /**
     *    Este es el objeto manejador de conexion a la base de datos
     * @access private
     * @var object
     */

    private $oConexion;

    /**
     *    Este son los datos devueltos, un objeto DB_result
     * @access private
     * @var object
     */

    private $oResultado;

    /**
     *    Este es el objeto que lleva las consultas
     * @access private
     * @var object
     */

    private $oQuery;

    // Associations
    // Operations

    /**
     * Manejador_Base_Datos constructor.
     * @param $login
     * @param $pass
     * @param $db
     * @param string $sServidorEtc
     * @param int $iPuertoEtc
     * @param string $sTipoBdEtc
     */

    function __construct($login, $pass, $db, $sServidorEtc='localhost', $iPuertoEtc=5432, $sTipoBdEtc='pgsql')
    {

        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "constructor", array('login=' . $login . ' pass=' . $pass . ' db=' . $db));
        }
        $this->sUser = trim($login);
        $this->sPasswd = trim($pass);
        $this->sDb = trim($db);
        $this->sHost = $sServidorEtc;
        $this->sPort = $iPuertoEtc;
        $this->sTipo_Bd = $sTipoBdEtc;
    }
    //Fin Manejador_Base_Datos

    /**
     *    Destructor
     *
     * @access public
     */
    function __destruct()
    {
        //    echo "destruyendo";
        if (\DB::isConnection($this->oConexion)) {
            $this->desconexion();
        }
        if (is_object($this->oQuery)) {
            $this->oQuery->__destruct();
        }
    }
    //Fin destructor

    /**
     *    Aqui controlamos todos los errores que puedan darse al realizar operaciones con
     *    la base de datos
     *
     * @access private
     * @param String $sMotivo
     * @param Mixed $mValor
     */

    private function manejo_Errores($sMotivo, $mValor = false)
    {
        switch ($sMotivo) {
            case 'conexion':
            $pera = new \PEAR();
                if ($pera->isError($this->oConexion)) {
                    global $iDebug;
                    if ($iDebug == 1) {
                        $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "manejo_errores", array($this->oResultado->message));
                        $_SESSION['sError'] = $_SESSION['oDebugger']->mostrar_Path();
                    }
                    $nombre_archivo = 'tmp/error.txt';
                    $contenido = "contenedor|" . print_r($this->oConexion);;

                    $gestor = fopen($nombre_archivo, 'w');
                    fwrite($gestor, $contenido);
                    fclose($gestor);


                    $length = strlen($contenido);
                    Header('Content-Type: text/plain');
                    Header('Content-Length: ' . $length);
                    Header('Content-Disposition: attachment; filename="error.txt"');
                    readfile($nombre_archivo);
                    unlink($nombre_archivo);

                    die();
                }
                break;
            case 'desconexion':

                if ($mValor == false) {
                    global $iDebug;
                    if ($iDebug == 1) {
                        $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "manejo_errores", array("Error al desconectar"));
                        $_SESSION['sError'] = $_SESSION['oDebugger']->mostrar_Path();
                    }
                    //echo "contenedor|desconexion";
                    $nombre_archivo = 'tmp/error.txt';
                    $contenido = "contenedor|Se ha producido un error en la aplicación, si persiste por favor pongase en contacto con el administrador";

                    $gestor = fopen($nombre_archivo, 'w');
                    fwrite($gestor, $contenido);
                    fclose($gestor);


                    $length = strlen($contenido);
                    Header('Content-Type: text/plain');
                    Header('Content-Length: ' . $length);
                    Header('Content-Disposition: attachment; filename="error.txt"');
                    readfile($nombre_archivo);
                    unlink($nombre_archivo);

                    die();
                }
                break;
            case 'consulta':
                if (\PEAR::isError($this->oResultado)) {
                    global $iDebug;
                    if ($iDebug == 1) {
                        $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "manejo_errores", array($this->oResultado->message));
                        $_SESSION['sError'] = $_SESSION['oDebugger']->mostrar_Path();
                    }


                    echo "contenedor|Se ha producido un error en la aplicación, si persiste por favor pongase en contacto con el administrador";
                    die();
                    //echo "contenedor|<iframe src=\"error.php?motivo=conexion\" width=\"100%\" height=\"600px\"" .
                    //"frameborder=\"0\" scrolling=\"auto\" style=\"z-index: 0\"><\iframe>";

                }
                break;
            case 'fetch':
                if (\PEAR::isError($mValor)) {
                    global $iDebug;
                    if ($iDebug == 1) {
                        $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "manejo_errores", array($this->oResultado->message));
                        $_SESSION['sError'] = $_SESSION['oDebugger']->mostrar_Path();
                    }
                    //echo "contenedor|a";print_r($this->oResultado);
                    //echo "contenedor|fetch";
                    $nombre_archivo = 'tmp/error.txt';
                    $contenido = "contenedor|" . print_r($this->oResultado, true);
                    $gestor = fopen($nombre_archivo, 'w');
                    fwrite($gestor, $contenido);
                    fclose($gestor);


                    $length = strlen($contenido);
                    Header('Content-Type: text/plain');
                    Header('Content-Length: ' . $length);
                    Header('Content-Disposition: attachment; filename="error.txt"');
                    readfile($nombre_archivo);
                    unlink($nombre_archivo);

                    //    echo "contenedor|Se ha producido un error en la aplicación, si persiste por favor pongase en contacto con el administrador";
                    die();
//                    echo "contenedor|<iframe src=\"error.php?motivo=conexion\" width=\"100%\" height=\"600px\"" .
                    //                   "frameborder=\"0\" scrolling=\"auto\" style=\"z-index: 0\"><\iframe>";
                }
                break;
        }
    }
    //Fin manejo_Errores


    /**
     *    Realiza una conexion y hace la consulta guardada en $oQuery
     *
     * @access public
     * @param null $sSql
     */

    public function consulta($sSql = null)
    {
        $sConsulta = $this->to_String_Consulta();
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "consulta", array("SQL=" . $sConsulta));
        }
        $this->conexion();
        if (is_null($sSql)) {
            $this->oResultado = $this->oConexion->query($sConsulta);
        } else {
            $this->oResultado = $this->oConexion->query($sSql);
        }
        $this->manejo_Errores('consulta');
    }
    //Fin consulta

    /**
     *    Este es nuestro fetchrow
     * @access public
     * @param bool $bSlash
     * @return array
     */
    public function coger_Fila($bSlash = true)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "coger_Fila", null);
        }
        $mTmp = $this->oResultado->fetchRow();
        if (is_array($mTmp)) {
            foreach ($mTmp as $sKey => $sValor) {
                if ($bSlash) {
                    $mTmp[$sKey] = stripslashes($sValor);
                } else {
                    $mTmp[$sKey] = $sValor;
                }
            }
        }
        $this->manejo_Errores('fetch', $mTmp);
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "coger_Fila=>Devuelve", array($mTmp));
        }
        return $mTmp;
    }
    //Fin coger_Columna


    /**
     *    Aqui realizamos la conexion con la base de datos
     *
     * @access private
     * @return string
     */
    public function dsn()
    {
        return $this->sTipo_Bd . "://" . $this->sUser . ":" . $this->sPasswd . "@" . $this->sHost . ":" . $this->sPort . "/" . $this->sDb;
    }

    public function conexion($tipo = 'persistente')
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "conexion", null);
        }

        if(isset($_SESSION['encodingapache'])) {
            $sEncoding = $_SESSION['encodingapache'];
        }
        else {
           $sEncoding ='UTF-8';
        }
        $dsn = $this->sTipo_Bd . "://" . $this->sUser . ":" . $this->sPasswd . "@" . $this->sHost . ":" . $this->sPort . "/" . $this->sDb;
        $this->oConexion = $this->connect($dsn, false);
        pg_set_client_encoding($this->oConexion->connection, $sEncoding);
        $this->manejo_Errores('conexion');
    }
    //Fin conexion

    /**
     *    Este es nuestro constructor para nuestro campo oQuery
     *
     * @access public
     * @param $sTipo
     */

    public function iniciar_Consulta($sTipo)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "iniciar_Consulta", array('Tipo=' . $sTipo));
        }
        $this->oQuery = new generador_SQL($sTipo);
    }
    //Fin iniciar_Consulta

    /**
     *    Este es nuestro metodo para añadir un campo select de una lista y que no sea el primero
     * @access public
     * @param string $sCampo
     */

    public function pon_Campo($sCampo)
    {
        $this->oQuery->nuevo_Campo($sCampo);
    }
    //Fin pon_Campo

    /**
     *      Este procedimiento recibe un array con todos los campos a seleccionar con nuestro select y los a�ade
     *      a nuestra consulta
     *
     * @access public
     * @param array $aCampos
     */

    public function construir_Campos($aCampos)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "construir_Campos", $aCampos);
        }
        if (is_array($aCampos)) {
            foreach ($aCampos as $sValor) {
                $this->pon_Campo($sValor);
            }
        }
    }
    //Fin construir_Campos

    /**
     *    Este es nuestro metodo para añadir una tabla
     *
     * @access public
     * @param String
     */

    public function pon_Tabla($sTabla)
    {
        $this->oQuery->nuevo_Tabla($sTabla);
    }

    //Fin pon_Tabla

    /**
     *    Este es nuestro metodo para poner las tablas sobre las que hacer la accion
     *
     * @access public
     * @param array $aTablas
     */

    public function construir_Tablas($aTablas)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "construir_Tablas", $aTablas);
        }
        if (is_array($aTablas)) {
            foreach ($aTablas as $sValor) {
                $this->pon_Tabla($sValor);
            }
        }
    }
    //Fin construir_Tablas


    /**
     *    Este es nuestro metodo añadir al 'where', por defecto pone condicion booleana con otros
     * campos del where a AND
     *
     * @access public
     * @param String $sWhere
     */

    public function pon_Where($sWhere)
    {
        $this->oQuery->nuevo_Where($sWhere);
    }
    //Fin pon_Where

    /**
     * @param $aWheres
     */

    public function construir_Where($aWheres)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "construir_Where", $aWheres);
        }
        if (is_array($aWheres)) {
            foreach ($aWheres as $sValor) {
                $this->pon_Where($sValor);
            }
        }
    }
    //Fin construir_Where

    /**
     *    Este es nuestro metodo añadir sentencias a un bloque BEGIN
     *
     * @access public
     * @param String $sSentencia
     */

    public function pon_Sentencia($sSentencia)
    {
        $this->oQuery->nuevo_Sentencia($sSentencia);
    }
    //Fin pon_Order

    /**
     *    Este metodo recibe un array de String y nos crea la parte BEGIN
     *
     * @access public
     * @param String $aBegins
     */

    public function construir_Begin($aBegins)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "construir_Begins", $aBegins);
        }
        if (is_array($aBegins)) {
            foreach ($aBegins as $sValor) {
                $this->pon_Sentencia($sValor);
            }
        }
    }


    /**
     *    Este es nuestro metodo añadir al campo ORDER BY
     *
     * @access public
     * @param String $sOrder
     */

    public function pon_Order($sOrder)
    {
        $this->oQuery->nuevo_Order($sOrder);
    }
    //Fin pon_Order

    /**
     * Este metodo recibe dun array de String y nos crea la parte order
     * @param array $aOrders
     */
    public function construir_Order($aOrders)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "construir_Orders", $aOrders);
        }
        if (is_array($aOrders)) {
            foreach ($aOrders as $sValor) {
                $this->pon_Order($sValor);
            }
        }
    }
    //Fin construir_Order

    /**
     *    Este es nuestro metodo añadir al campo GROUP BY
     *
     * @access public
     * @param String $sGroup
     */

    public function pon_Group($sGroup)
    {
        $this->oQuery->nuevo_Group($sGroup);
    }
    //Fin pon_Group

    /**
     *    Este metodo recibe dun array de String y nos crea la parte Group
     *
     * @access public
     * @param array $aGroups
     */

    public function construir_Group($aGroups)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "construir_Groups", $aGroups);
        }
        if (is_array($aGroups)) {
            foreach ($aGroups as $sValor) {
                $this->pon_Group($sValor);
            }
        }
    }
    //Fin construir_Group

    /**
     *    Este es nuestro metodo añadir al campo VALUE
     *
     * @access public
     * @param String $sValue
     */

    public function pon_Value($sValue)
    {
        $this->oQuery->nuevo_Value($sValue);
    }

    public function pon_ValueSin($sValue)
    {
        $this->oQuery->nuevo_ValueSin($sValue);
    }

    public function pon_ValueSinSlash($sValue)
    {
        $this->oQuery->nuevo_ValueSinSlashes($sValue);
    }
    //Fin pon_Value

    /**
     * @param array $aValues
     */

    public function construir_Value($aValues)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "construir_Values", $aValues);
        }
        if (is_array($aValues)) {
            foreach ($aValues as $sValor) {
                $this->pon_Value($sValor);
            }
        }
    }

    /**
     * @param array $aValues
     */
    public function construir_ValueSin($aValues)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "construir_Values", $aValues);
        }
        if (is_array($aValues)) {
            foreach ($aValues as $sValor) {
                $this->pon_ValueSin($sValor);
            }
        }
    }

    public function construir_ValueSinSlash($aValues)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "construir_Values", $aValues);
        }
        if (is_array($aValues)) {
            foreach ($aValues as $sValor) {
                $this->pon_ValueSinSlash($sValor);
            }
        }
    }
    //Fin construir_Value

    /**
     *    Este es nuestro metodo añadir al campo SET
     *
     * @access public
     * @param String $sSet
     */

    public function pon_Set($sSet, $sValue)
    {
        $this->oQuery->nuevo_Set($sSet, $sValue);
    }

    /**
     * @param $sSet
     * @param $sValue
     */
    public function pon_SetSin($sSet, $sValue)
    {
        $this->oQuery->nuevo_SetSin($sSet, $sValue);
    }

    /**
     * @param $sSet
     * @param $sValue
     */
    public function pon_SetSlashes($sSet, $sValue)
    {
        $this->oQuery->nuevo_SetSlashes($sSet, $sValue);
    }

    /**
     *    Este es nuestro metodo añadir un select al insert
     *
     * @access public
     * @param String $sSelect
     */
    public function pon_Select($sSelect)
    {
        $this->oQuery->nuevo_Select($sSelect);
    }
    //Fin pon_Select


    /**
     *    Este metodo recibe dun array de String y nos crea la parte SET
     *
     * @access public
     * @param array $aSets
     */

    public function construir_Set($aSets, $aValues)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "construir_Sets", $aSets);
        }
        if (is_array($aSets)) {
            foreach ($aSets as $sKey => $sValor) {
                $this->pon_Set($sValor, $aValues[$sKey]);
            }
        }
    }

    /**
     * @param $aSets
     * @param $aValues
     */
    public function construir_SetSin($aSets, $aValues)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "construir_Sets", $aSets);
        }
        if (is_array($aSets)) {
            foreach ($aSets as $sKey => $sValor) {
                $this->pon_SetSin($sValor, $aValues[$sKey]);
            }
        }
    }

    /**
     * @param $aSets
     * @param $aValues
     */
    public function construir_SetSlashes($aSets, $aValues)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "construir_Sets", $aSets);
        }
        if (is_array($aSets)) {
            foreach ($aSets as $sKey => $sValor) {
                $this->pon_SetSlashes($sValor, $aValues[$sKey]);
            }
        }
    }
    //Fin construir_Set

    /**
     *     Hacemos commit
     *
     */

    public function hacer_Commit()
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "Commit", array("SQL=Commit"));
        }
        $this->conexion();

        $this->oResultado = $this->oConexion->query("COMMIT;");
        $this->manejo_Errores('consulta');
    }
    //Fin hacer_Commit

    /**
     *     Hacemos rollback
     *
     */

    public function hacer_Rollback()
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_Base_Datos.class.php", "Rollback", array("SQL=Rollback"));
        }
        $this->conexion();

        $this->oResultado = $this->oConexion->query("ROLLBACK;");
        $this->manejo_Errores('consulta');
    }
    //Fin hacer_Rollback


    /**
     *
     * Para hacer las transacciones usamos estos dos metodos que nos permiten tener seguridad de que si no se completa
     *
     */

    public function comienza_transaccion()
    {
        $this->conexion();
        $this->oResultado = $this->oConexion->query("BEGIN;");
        $this->oConexion->autoCommit(false);
        $this->manejo_Errores('consulta');
    }

    public function termina_transaccion()
    {
        $this->conexion();
        $this->oResultado = $this->oConexion->query("END;");
        $this->oConexion->autoCommit(true);
        $this->manejo_Errores('consulta');
    }


    /**
     *   Nos devuelve la cadena sql guardada
     *
     * @access public
     * @return string
     */

    public function to_String_Consulta()
    {
        return $this->oQuery->to_String();
    }
    //Fin to_String_Consulta

    /**
     *    Desconexion de la base de datos
     *
     * @access public
     */

    public function desconexion()
    {
        $sValor = $this->oConexion->disconnect();
        //$this->manejo_Errores('desconexion',$sValor);
    }
    //Fin desconexion

    /**
     *     Creacion de un LOB
     *
     * @access public
     */
    public function crear_LOB()
    {
        return pg_lo_create($this->oConexion->connection);
    }

    /**
     *     Abrir un LOB
     *
     * @access public
     */
    public function abrir_LOB($iOid, $sModo)
    {
        return pg_lo_open($this->oConexion->connection, $iOid, $sModo);
    }

    /**
     *     Escribir un LOB
     *
     * @access public
     */
    public function escribir_LOB($iBlob, $sContenido)
    {
        return pg_lo_write($iBlob, $sContenido);
    }

    /**
     *     Cerrar un LOB
     *
     * @access public
     */
    public function cerrar_LOB($iBlob)
    {
        return pg_lo_close($iBlob);
    }

    /**
     *     Destruir un LOB
     *
     * @access public
     */
    public function destruir_LOB($iBlob)
    {
        return pg_lo_unlink($this->oConexion->connection, $iBlob);
    }

    /**
     *     Destruir un LOB
     *
     * @access public
     */
    public function leer_LOB_Completo($iBlob)
    {
        pg_lo_read_all($iBlob);
    }

    public function leer_LOB_Imagen($iBlob, $sLength)
    {
        return (pg_lo_read($iBlob, $sLength));
    }
}
