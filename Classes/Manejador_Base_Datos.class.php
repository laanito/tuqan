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
class Manejador_Base_Datos extends \PDO
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
     *    Este son los datos devueltos, un objeto DB_result
     * @access private
     * @var \PDOStatement
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
        $this->sUser = trim($login);
        $this->sPasswd = trim($pass);
        $this->sDb = trim($db);
        $this->sHost = $sServidorEtc;
        $this->sPort = $iPuertoEtc;
        $this->sTipo_Bd = $sTipoBdEtc;
        parent::__construct($this->dsn());
    }
    //Fin Manejador_Base_Datos


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
        if (is_a($this, 'PEAR_Error')) {
            TuqanLogger::error(
                'Database Error',
                ['smotivo' => $sMotivo, 'mvalor' => $mValor, 'error' => $this->errorInfo()]
            );
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
        if (is_null($sSql)) {
            $this->oResultado = $this->query($sConsulta);
        } else {
            $this->oResultado = $this->query($sSql);
        }
        $this->manejo_Errores('consulta');
    }
    //Fin consulta

    /**
     *    Este es nuestro fetchrow
     * @access public
     * @param bool $bSlash
     * @param integer $mode fetch mode
     * @return array
     */
    public function coger_Fila($bSlash = true, $mode = \PDO::FETCH_NUM)
    {
        $mTmp = $this->oResultado->fetch($mode);
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
        $dsn = $this->sTipo_Bd . ':' . 'dbname='. $this->sDb . ';'.
            'user='.$this->sUser .';'.'password='.$this->sPasswd.
            ';'.'host='.$this->sHost;
        return $dsn;
    }

    /**
     * @param string $tipo
     */
    public function conexion()
    {
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
     * @param array $aBegins
     */
    public function construir_Begin($aBegins)
    {
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

    /**
     * @param $sValue
     */
    public function pon_ValueSin($sValue)
    {
        $this->oQuery->nuevo_ValueSin($sValue);
    }

    /**
     * @param $sValue
     */
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
        if (is_array($aValues)) {
            foreach ($aValues as $sValor) {
                $this->pon_ValueSin($sValor);
            }
        }
    }

    public function construir_ValueSinSlash($aValues)
    {
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
     * @param string $sSet
     * @param string $sValue
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
        $this->conexion();

        $this->oResultado = $this->query("COMMIT;");
        $this->manejo_Errores('consulta');
    }
    //Fin hacer_Commit

    /**
     *     Hacemos rollback
     *
     */

    public function hacer_Rollback()
    {
        $this->conexion();

        $this->oResultado = $this->query("ROLLBACK;");
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
        $this->oResultado = $this->query("BEGIN;");
        $this->autoCommit(false);
        $this->manejo_Errores('consulta');
    }

    /**
     *
     */
    public function termina_transaccion()
    {
        $this->conexion();
        $this->oResultado = $this->query("END;");
        $this->autoCommit(true);
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
        $this->disconnect();
    }
    //Fin desconexion

    /**
     *     Creacion de un LOB
     *
     * @access public
     */
    public function crear_LOB()
    {
        return $this->pgsqlLOBCreate($this->connection);
    }

    /**
     *     Abrir un LOB
     *
     * @access public
     */
    public function abrir_LOB($iOid, $sModo)
    {
        return $this->pgsqlLOBOpen($iOid, $sModo);
    }

    /**
     *     Escribir un LOB
     *
     * @access public
     */
    public function escribir_LOB($iBlob, $sContenido)
    {
        return stream_copy_to_stream($sContenido, $iBlob);
    }

    /**
     *     Cerrar un LOB
     *
     * @access public
     */
    public function cerrar_LOB($iBlob)
    {
        return true;
    }

    /**
     *     Destruir un LOB
     *
     * @access public
     */
    public function destruir_LOB($iBlob)
    {
        return $this->pgsqlLOBUnlink($iBlob);
    }

    /**
     *     Destruir un LOB
     *
     * @access public
     */
    public function leer_LOB_Completo($iBlob)
    {
        $stream = $this->pgsqlLOBOpen($iBlob, 'r');
        header("Content-type: application/octet-stream");
        fpassthru($stream);
    }

    public function leer_LOB_Imagen($iBlob, $sLength)
    {
        $stream = $this->pgsqlLOBOpen($iBlob, 'r');
        header("Content-type: application/octet-stream");
        fpassthru($stream);
    }
}
