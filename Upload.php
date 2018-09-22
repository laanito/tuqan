<?php
// **********************************************
//
// This software is licensed by the LGPL
// -> http://www.gnu.org/copyleft/lesser.txt
// (c) 2001-2004 by Tomas Von Veschler Cox
//
// **********************************************
//
// $Id: Upload.php,v 1.1 2006-08-31 08:36:04 jmartinez Exp $

/*
 * Pear File Uploader class. Easy and secure managment of files
 * submitted via HTML Forms.
 *
 * Leyend:
 * - you can add error msgs in your language in the HTTP_Upload_Error class
 *
 * TODO:
 * - try to think a way of having all the Error system in other
 *   file and only include it when an error ocurrs
 *
 * -- Notes for users HTTP_Upload >= 0.9.0 --
 *
 *  Error detection was enhanced, so you no longer need to
 *  check for PEAR::isError() in $upload->getFiles() or call
 *  $upload->isMissing(). Instead you'll
 *  get the error when do a check for $file->isError().
 *
 *  Example:
 *
 *  $upload = new HTTP_Upload('en');
 *  $file = $upload->getFiles('i_dont_exist_in_form_definition');
 *  if ($file->isError()) {
 *      die($file->getMessage());
 *  }
 *
 *  --
 *
 */

require_once 'PEAR.php';

/**
 * defines default chmod
 */
define('HTTP_UPLOAD_DEFAULT_CHMOD', 0660);

/**
 * Error Class for HTTP_Upload
 *
 * @author  Tomas V.V.Cox <cox@idecnet.com>
 * @see http://vulcanonet.com/soft/index.php?pack=uploader
 * @package HTTP_Upload
 * @category HTTP
 * @access public
 */
class HTTP_Upload_Error extends PEAR
{
    /**
     * Selected language for error messages
     * @var string
     */
    var $lang = 'en';

    /**
     * Whether HTML entities shall be encoded automatically
     * @var boolean
     */
    var $html = false;

    /**
     * Constructor
     *
     * Creates a new PEAR_Error
     *
     * @param string $lang The language selected for error code messages
     * @access public
     */
    function HTTP_Upload_Error($lang = null, $html = false)
    {
        $this->lang = ($lang !== null) ? $lang : $this->lang;
        $this->html = ($html !== false) ? $html : $this->html;
        $ini_size = preg_replace('/m/i', '000000', ini_get('upload_max_filesize'));

        if (function_exists('version_compare') &&
            version_compare(phpversion(), '4.1', 'ge')) {
            $maxsize = (isset($_POST['MAX_FILE_SIZE'])) ?
                $_POST['MAX_FILE_SIZE'] : null;
        } else {
            global $HTTP_POST_VARS;
            $maxsize = (isset($HTTP_POST_VARS['MAX_FILE_SIZE'])) ?
                $HTTP_POST_VARS['MAX_FILE_SIZE'] : null;
        }

        if (empty($maxsize) || ($maxsize > $ini_size)) {
            $maxsize = $ini_size;
        }
        // XXXXX Add here error messages in your language
        $this->error_codes = array(
            'TOO_LARGE' => array(
                'es' => "Fichero demasiado largo. El maximo permitido es: $maxsize bytes.",
                'en' => "File size too large. The maximum permitted size is: $maxsize bytes.",
                'de' => "Datei zu gro&szlig;. Die zul&auml;ssige Maximalgr&ouml;&szlig;e ist: $maxsize Bytes.",
                'nl' => "Het bestand is te groot, de maximale grootte is: $maxsize bytes.",
                'fr' => "Le fichier est trop gros. La taille maximum autoris&eacute;e est: $maxsize bytes.",
                'it' => "Il file &eacute; troppo grande. Il massimo permesso &eacute: $maxsize bytes.",
                'pt_BR' => "Arquivo muito grande. O tamanho m&aacute;ximo permitido &eacute; $maxsize bytes.",
                'sv' => "Filen &auml;r f&ouml;r stor. St&ouml;rsta till&aring;tna filstorlek &auml;r: $maxsize bytes",
                'da' => "Filen er for stor. St&oslash;rste tilladte filst&oslash;rrelse er : $maxsize bytes"
            ),
            'MISSING_DIR' => array(
                'es' => 'Falta directorio destino.',
                'en' => 'Missing destination directory.',
                'de' => 'Kein Zielverzeichnis definiert.',
                'nl' => 'Geen bestemmings directory.',
                'fr' => 'Le r&eacute;pertoire de destination n\'est pas d&eacute;fini.',
                'it' => 'Manca la directory di destinazione.',
                'pt_BR' => 'Aus&ecirc;ncia de diret&oacute;rio de destino.',
                'sv' => "Saknar destinationskatalog",
                'da' => "Mangler destinationskatalog"
            ),
            'IS_NOT_DIR' => array(
                'es' => 'El directorio destino no existe o es un fichero regular.',
                'en' => 'The destination directory doesn\'t exist or is a regular file.',
                'de' => 'Das angebene Zielverzeichnis existiert nicht oder ist eine Datei.',
                'nl' => 'De doeldirectory bestaat niet, of is een gewoon bestand.',
                'fr' => 'Le r&eacute;pertoire de destination n\'existe pas ou il s\'agit d\'un fichier r&eacute;gulier.',
                'it' => 'La directory di destinazione non esiste o &eacute; un file.',
                'pt_BR' => 'O diret&oacute;rio de destino n&atilde;o existe ou &eacute; um arquivo.',
                'sv' => "Destinationskatalogen existerar inte, eller &auml;r en vanlig fil",
                'da' => "Destinationskatalogen eksisterer ikke, eller er en almindelig fil"
            ),
            'NO_WRITE_PERMS' => array(
                'es' => 'El directorio destino no tiene permisos de escritura.',
                'en' => 'The destination directory doesn\'t have write perms.',
                'de' => 'Fehlende Schreibrechte f&uuml;r das Zielverzeichnis.',
                'nl' => 'Geen toestemming om te schrijven in de doeldirectory.',
                'fr' => 'Le r&eacute;pertoire de destination n\'a pas les droits en &eacute;criture.',
                'it' => 'Non si hanno i permessi di scrittura sulla directory di destinazione.',
                'pt_BR' => 'O diret&oacute;rio de destino n&atilde;o possui permiss&atilde;o para escrita.',
                'sv' => 'Destinationskatalogen har inte skrivr&auml;ttigheter',
                'da' => 'Destinationskatalogen har ikke skrivrettigheder'
            ),
            'NO_USER_FILE' => array(
                'es' => 'No se ha escogido fichero para el upload.',
                'en' => 'You haven\'t selected any file for uploading.',
                'de' => 'Es wurde keine Datei f&uuml;r den Upload ausgew&auml;hlt.',
                'nl' => 'Er is geen bestand opgegeven om te uploaden.',
                'fr' => 'Vous n\'avez pas s&eacute;lectionn&eacute; de fichier &agrave; envoyer.',
                'it' => 'Nessun file selezionato per l\'upload.',
                'pt_BR' => 'Nenhum arquivo selecionado para upload.',
                'sv' => 'Du har inte valt n&aring;gon fil att ladda upp',
                'da' => 'Du har ikke valgt nogen fil at uploade'
            ),
            'BAD_FORM' => array(
                'es' => 'El formulario no contiene method="post" enctype="multipart/form-data" requerido.',
                'en' => 'The html form doesn\'t contain the required method="post" enctype="multipart/form-data".',
                'de' => 'Das HTML-Formular enth&auml;lt nicht die Angabe method="post" enctype="multipart/form-data" ' .
                    'im &gt;form&lt;-Tag.',
                'nl' => 'Het HTML-formulier bevat niet de volgende benodigde ' .
                    'eigenschappen: method="post" enctype="multipart/form-data".',
                'fr' => 'Le formulaire HTML ne contient pas les attributs requis : ' .
                    ' method="post" enctype="multipart/form-data".',
                'it' => 'Il modulo HTML non contiene gli attributi richiesti: "' .
                    ' method="post" enctype="multipart/form-data".',
                'pt_BR' => 'O formul&aacute;rio HTML n&atilde;o possui o method="post" enctype="multipart/form-data" requerido.',
                'sv' => 'HTML-formul&auml;ret inneh&aring;ller inte de attribut som kr&auml;vs: ' .
                    ' method="post" enctype="multipart/form-data"',
                'da' => 'HTML-formularen mangler disse indstillinger: ' .
                    ' method="post" enctype="multipart/form-data"'
            ),
            'E_FAIL_COPY' => array(
                'es' => 'Fallo al copiar el fichero temporal.',
                'en' => 'Failed to copy the temporary file.',
                'de' => 'Tempor&auml;re Datei konnte nicht kopiert werden.',
                'nl' => 'Het tijdelijke bestand kon niet gekopieerd worden.',
                'fr' => 'L\'enregistrement du fichier temporaire a &eacute;chou&eacute;.',
                'it' => 'Copia del file temporaneo fallita.',
                'pt_BR' => 'Falha ao copiar o arquivo tempor&aacute;rio.',
                'sv' => 'Misslyckades med att kopiera den tempor&auml;ra filen',
                'da' => 'Det lykkedes ikke at kopiere den tempor&aelig;re fil'
            ),
            'E_FAIL_MOVE' => array(
                'es' => 'No puedo mover el fichero.',
                'en' => 'Impossible to move the file.',
                'de' => 'Datei kann nicht verschoben werden.',
                'nl' => 'Het bestand kon niet verplaatst worden.',
                'fr' => 'Impossible de d&eacute;placer le fichier.',
                'pt_BR' => 'N&atilde;o foi poss&iacute;vel mover o arquivo.',
                'sv' => 'Misslyckades med att flytta den tempor&auml;ra filen',
                'da' => 'Det lykkedes ikke at flytta den tempor&aelig;re fil'
            ),
            'FILE_EXISTS' => array(
                'es' => 'El fichero destino ya existe.',
                'en' => 'The destination file already exists.',
                'de' => 'Die zu erzeugende Datei existiert bereits.',
                'nl' => 'Het doelbestand bestaat al.',
                'fr' => 'Le fichier de destination existe d&eacute;j&agrave;.',
                'it' => 'File destinazione gi&agrave; esistente.',
                'pt_BR' => 'O arquivo de destino j&aacute; existe.',
                'sv' => 'Destinationsfilen existerar redan',
                'da' => 'Destinationsfilen findes allerede'
            ),
            'CANNOT_OVERWRITE' => array(
                'es' => 'El fichero destino ya existe y no se puede sobreescribir.',
                'en' => 'The destination file already exists and could not be overwritten.',
                'de' => 'Die zu erzeugende Datei existiert bereits und konnte nicht &uuml;berschrieben werden.',
                'nl' => 'Het doelbestand bestaat al, en kon niet worden overschreven.',
                'fr' => 'Le fichier de destination existe d&eacute;j&agrave; et ne peux pas &ecirc;tre remplac&eacute;.',
                'it' => 'File destinazione gi&agrave; esistente e non si pu&ograve; sovrascrivere.',
                'pt_BR' => 'O arquivo de destino j&aacute; existe e n&atilde;o p&ocirc;de ser sobrescrito.',
                'sv' => 'Destinationsfilen existerar redan och kunde inte skrivas &ouml;ver',
                'da' => 'Destinationsfilen findes allerede og kunne ikke overskrives'
            ),
            'NOT_ALLOWED_EXTENSION' => array(
                'es' => 'Extension de fichero no permitida.',
                'en' => 'File extension not permitted.',
                'de' => 'Unerlaubte Dateiendung.',
                'nl' => 'Niet toegestane bestands-extensie.',
                'fr' => 'Le fichier a une extension non autoris&eacute;e.',
                'it' => 'Estensione del File non permessa.',
                'pt_BR' => 'Extens&atilde;o de arquivo n&atilde;o permitida.',
                'sv' => 'Ej till&aring;ten fil&auml;ndelse',
                'da' => 'Ikke tilladt filformat'
            ),
            'PARTIAL' => array(
                'es' => 'El fichero fue parcialmente subido',
                'en' => 'The file was only partially uploaded.',
                'de' => 'Die Datei wurde unvollst&auml;ndig &uuml;bertragen.',
                'nl' => 'Het bestand is slechts gedeeltelijk geupload.',
                'pt_BR' => 'O arquivo n&atilde;o foi enviado por completo.',
                'sv' => 'Filen blev endast delvis uppladdad.',
                'da' => 'Filen blev kun delvis uploadet.'
            ),
            'ERROR' => array(
                'es' => 'Error en subida:',
                'en' => 'Upload error:',
                'de' => 'Fehler beim Upload:',
                'nl' => 'Upload fout:',
                'pt_BR' => 'Erro de upload:',
                'sv' => 'Fel vid upladdning:',
                'da' => 'Fejl ved upload:'
            ),
            'DEV_NO_DEF_FILE' => array(
                'es' => 'No est&aacute; definido en el formulario este nombre de fichero como &lt;input type="file" name=?&gt;.',
                'en' => 'This filename is not defined in the form as &lt;input type="file" name=?&gt;.',
                'de' => 'Dieser Dateiname ist im Formular nicht als &lt;input type="file" name=?&gt; definiert.',
                'nl' => 'Deze bestandsnaam is niett gedefineerd in het formulier als &lt;input type="file" name=?&gt;.',
                'pt_BR' => 'Este arquivo n&atilde;o foi definido no formul&aacute;rio como  &lt;input type="file" name=?&gt;.',
                'sv' => 'Detta filnamn &auml;r inte definierat i formul&auml;ret som &lt;input type="file" name=?&gt;.',
                'da' => 'Dette filnavn er ikke definieret i formularen som &lt;input type="file" name=?&gt;.'
            )
        );
    }

    /**
     * returns the error code
     *
     * @param    string $e_code type of error
     * @return   string          Error message
     */
    function errorCode($e_code)
    {
        if (!empty($this->error_codes[$e_code][$this->lang])) {
            $msg = $this->html ?
                html_entity_decode($this->error_codes[$e_code][$this->lang]) :
                $this->error_codes[$e_code][$this->lang];
        } else {
            $msg = $e_code;
        }

        if (!empty($this->error_codes['ERROR'][$this->lang])) {
            $error = $this->error_codes['ERROR'][$this->lang];
        } else {
            $error = $this->error_codes['ERROR']['en'];
        }
        return $error . ' ' . $msg;
    }

    /**
     * Overwrites the PEAR::raiseError method
     *
     * @param    string $e_code type of error
     * @return   object PEAR_Error   a PEAR-Error object
     * @access   public
     */
    function raiseError($e_code)
    {
        return PEAR::raiseError($this->errorCode($e_code), $e_code);
    }
}

/**
 * This class provides an advanced file uploader system
 * for file uploads made from html forms
 *
 * @author  Tomas V.V.Cox <cox@idecnet.com>
 * @see http://vulcanonet.com/soft/index.php?pack=uploader
 * @package  HTTP_Upload
 * @category HTTP
 * @access   public
 */
class HTTP_Upload extends HTTP_Upload_Error
{
    /**
     * Contains an array of "uploaded files" objects
     * @var array
     */
    var $files = array();

    /**
     * Whether the files array has already been built or not
     * @var int
     * @access private
     */
    var $is_built = false;

    /**
     * Contains the desired chmod for uploaded files
     * @var int
     * @access private
     */
    var $_chmod = HTTP_UPLOAD_DEFAULT_CHMOD;

    /**
     * Specially used if the naming mode is 'seq'
     * Contains file naming information
     *
     * @var array
     * @access private
     */
    var $_modeNameSeq = array(
        'flag' => false,
        'prepend' => '',
        'append' => '',
    );

    /**
     * Constructor
     *
     * @param string $lang Language to use for reporting errors
     * @see Upload_Error::error_codes
     * @access public
     */
    function HTTP_Upload($lang = null)
    {
        $this->HTTP_Upload_Error($lang);
        if (function_exists('version_compare') &&
            version_compare(phpversion(), '4.1', 'ge')) {
            $this->post_files = $_FILES;
            if (isset($_SERVER['CONTENT_TYPE'])) {
                $this->content_type = $_SERVER['CONTENT_TYPE'];
            }
        } else {
            global $HTTP_POST_FILES, $HTTP_SERVER_VARS;
            $this->post_files = $HTTP_POST_FILES;
            if (isset($HTTP_SERVER_VARS['CONTENT_TYPE'])) {
                $this->content_type = $HTTP_SERVER_VARS['CONTENT_TYPE'];
            }
        }
    }

    /**
     * Get files
     *
     * @param mixed $file If:
     *    - not given, function will return array of upload_file objects
     *    - is int, will return the $file position in upload_file objects array
     *    - is string, will return the upload_file object corresponding
     *        to $file name of the form. For ex:
     *        if form is <input type="file" name="userfile">
     *        to get this file use: $upload->getFiles('userfile')
     *
     * @return mixed array or object (see @param $file above) or Pear_Error
     * @access public
     */
    function &getFiles($file = null)
    {
        //build only once for multiple calls
        if (!$this->is_built) {
            $files = &$this->_buildFiles();
            if (\PEAR::isError($files)) {
                // there was an error with the form.
                // Create a faked upload embedding the error
                $files_code = $files->getCode();
                $this->files['_error'] =  &new HTTP_Upload_File(
                    '_error', null,
                    null, null,
                    null, $files_code,
                    $this->lang, $this->_chmod);
            } else {
                $this->files = $files;
            }
            $this->is_built = true;
        }
        if ($file !== null) {
            if (is_int($file)) {
                $pos = 0;
                foreach ($this->files as $obj) {
                    if ($pos == $file) {
                        return $obj;
                    }
                    $pos++;
                }
            } elseif (is_string($file) && isset($this->files[$file])) {
                return $this->files[$file];
            }
            if (isset($this->files['_error'])) {
                return $this->files['_error'];
            } else {
                // developer didn't specify this name in the form
                // warn him about it with a faked upload
                return new HTTP_Upload_File(
                    '_error', null,
                    null, null,
                    null, 'DEV_NO_DEF_FILE',
                    $this->lang);
            }
        }
        return $this->files;
    }

    /**
     * Creates the list of the uploaded file
     *
     * @return array of HTTP_Upload_File objects for every file
     */
    function &_buildFiles()
    {
        // Form method check
        if (!isset($this->content_type) ||
            strpos($this->content_type, 'multipart/form-data') !== 0) {
            $error = $this->raiseError('BAD_FORM');

            return $error;
        }
        // In 4.1 $_FILES isn't initialized when no uploads
        // XXX (cox) afaik, in >= 4.1 and <= 4.3 only
        if (function_exists('version_compare') &&
            version_compare(phpversion(), '4.1', 'ge')) {
            $error = $this->isMissing();
            if (\PEAR::isError($error)) {
                return $error;
            }
        }

        // map error codes from 4.2.0 $_FILES['userfile']['error']
        if (function_exists('version_compare') &&
            version_compare(phpversion(), '4.2.0', 'ge')) {
            $uploadError = array(
                1 => 'TOO_LARGE',
                2 => 'TOO_LARGE',
                3 => 'PARTIAL',
                4 => 'NO_USER_FILE'
            );
        }


        // Parse $_FILES (or $HTTP_POST_FILES)
        $files = array();
        foreach ($this->post_files as $userfile => $value) {
            if (is_array($value['name'])) {
                foreach ($value['name'] as $key => $val) {
                    $err = $value['error'][$key];
                    if (isset($err) && $err !== 0 && isset($uploadError[$err])) {
                        $error = $uploadError[$err];
                    } else {
                        $error = null;
                    }
                    $name = basename($value['name'][$key]);
                    $tmp_name = $value['tmp_name'][$key];
                    $size = $value['size'][$key];
                    $type = $value['type'][$key];
                    $formname = $userfile . "[$key]";
                    $files[$formname] = new HTTP_Upload_File($name, $tmp_name,
                        $formname, $type, $size, $error, $this->lang, $this->_chmod);
                }
                // One file
            } else {
                $err = $value['error'];
                if (isset($err) && $err !== 0 && isset($uploadError[$err])) {
                    $error = $uploadError[$err];
                } else {
                    $error = null;
                }
                $name = basename($value['name']);
                $tmp_name = $value['tmp_name'];
                $size = $value['size'];
                $type = $value['type'];
                $formname = $userfile;
                $files[$formname] = new HTTP_Upload_File($name, $tmp_name,
                    $formname, $type, $size, $error, $this->lang, $this->_chmod);
            }
        }
        return $files;
    }

    /**
     * Checks if the user submited or not some file
     *
     * @return mixed False when are files or PEAR_Error when no files
     * @access public
     * @see Read the note in the source code about this function
     */
    function isMissing()
    {
        if (count($this->post_files) < 1) {
            return $this->raiseError('NO_USER_FILE');
        }
        //we also check if at least one file has more than 0 bytes :)
        $files = array();
        $size = 0;
        $error = null;

        foreach ($this->post_files as $userfile => $value) {
            if (is_array($value['name'])) {
                foreach ($value['name'] as $key => $val) {
                    $size += $value['size'][$key];
                }
            } else {  //one file
                $size = $value['size'];
                $error = $value['error'];
            }
        }
        if ($error != 2 && $size == 0) {
            return $this->raiseError('NO_USER_FILE');
        }
        return false;
    }

    /**
     * Sets the chmod to be used for uploaded files
     *
     * @param int Desired mode
     */
    function setChmod($mode)
    {
        $this->_chmod = $mode;
    }
}

/**
 * This class provides functions to work with the uploaded file
 *
 * @author  Tomas V.V.Cox <cox@idecnet.com>
 * @see http://vulcanonet.com/soft/index.php?pack=uploader
 * @package  HTTP_Upload
 * @category HTTP
 * @access   public
 */
class HTTP_Upload_File extends HTTP_Upload_Error
{
    /**
     * If the random seed was initialized before or not
     * @var  boolean;
     */
    var $_seeded = 0;

    /**
     * Assoc array with file properties
     * @var array
     */
    var $upload = array();

    /**
     * If user haven't selected a mode, by default 'safe' will be used
     * @var boolean
     */
    var $mode_name_selected = false;

    /**
     * It's a common security risk in pages who has the upload dir
     * under the document root (remember the hack of the Apache web?)
     *
     * @var array
     * @access private
     * @see HTTP_Upload_File::setValidExtensions()
     */
    var $_extensionsCheck = array('php', 'phtm', 'phtml', 'php3', 'inc');

    /**
     * @see HTTP_Upload_File::setValidExtensions()
     * @var string
     * @access private
     */
    var $_extensionsMode = 'deny';

    /**
     * Contains the desired chmod for uploaded files
     * @var int
     * @access private
     */
    var $_chmod = HTTP_UPLOAD_DEFAULT_CHMOD;

    /**
     * Constructor
     *
     * @param   string $name destination file name
     * @param   string $tmp temp file name
     * @param   string $formname name of the form
     * @param   string $type Mime type of the file
     * @param   string $size size of the file
     * @param   string $error error on upload
     * @param   string $lang used language for errormessages
     * @access  public
     */
    function HTTP_Upload_File($name = null, $tmp = null, $formname = null,
                              $type = null, $size = null, $error = null,
                              $lang = null, $chmod = HTTP_UPLOAD_DEFAULT_CHMOD)
    {
        $this->HTTP_Upload_Error($lang);
        $ext = null;

        if (empty($name) || ($error != 'TOO_LARGE' && $size == 0)) {
            $error = 'NO_USER_FILE';
        } elseif ($tmp == 'none') {
            $error = 'TOO_LARGE';
        } else {
            // strpos needed to detect files without extension
            if (($pos = strrpos($name, '.')) !== false) {
                $ext = substr($name, $pos + 1);
            }
        }

        if (function_exists('version_compare') &&
            version_compare(phpversion(), '4.1', 'ge')) {
            if (isset($_POST['MAX_FILE_SIZE']) &&
                $size > $_POST['MAX_FILE_SIZE']) {
                $error = 'TOO_LARGE';
            }
        } else {
            global $HTTP_POST_VARS;
            if (isset($HTTP_POST_VARS['MAX_FILE_SIZE']) &&
                $size > $HTTP_POST_VARS['MAX_FILE_SIZE']) {
                $error = 'TOO_LARGE';
            }
        }

        $this->upload = array(
            'real' => $name,
            'name' => $name,
            'form_name' => $formname,
            'ext' => $ext,
            'tmp_name' => $tmp,
            'size' => $size,
            'type' => $type,
            'error' => $error
        );

        $this->_chmod = $chmod;
    }

    /**
     * Sets the name of the destination file
     *
     * @param string $mode A valid mode: 'uniq', 'seq', 'safe' or 'real' or a file name
     * @param string $prepend A string to prepend to the name
     * @param string $append A string to append to the name
     *
     * @return string The modified name of the destination file
     * @access public
     */
    function setName($mode, $prepend = null, $append = null)
    {
        switch ($mode) {
            case 'uniq':
                $name = $this->nameToUniq();
                $this->upload['ext'] = $this->nameToSafe($this->upload['ext'], 10);
                $name .= '.' . $this->upload['ext'];
                break;
            case 'safe':
                $name = $this->nameToSafe($this->upload['real']);
                if (($pos = strrpos($name, '.')) !== false) {
                    $this->upload['ext'] = substr($name, $pos + 1);
                } else {
                    $this->upload['ext'] = '';
                }
                break;
            case 'real':
                $name = $this->upload['real'];
                break;
            case 'seq':
                $this->_modeNameSeq['flag'] = true;
                $this->_modeNameSeq['prepend'] = $prepend;
                $this->_modeNameSeq['append'] = $append;
                break;
            default:
                $name = $mode;
        }
        $this->upload['name'] = $prepend . $name . $append;
        $this->mode_name_selected = true;
        return $this->upload['name'];
    }

    /**
     * Sequence file names in the form: userGuide[1].pdf, userGuide[2].pdf ...
     *
     * @param string $dir Destination directory
     */
    function nameToSeq($dir)
    {
        //Check if a file with the same name already exists
        $name = $dir . DIRECTORY_SEPARATOR . $this->upload['real'];
        if (!@is_file($name)) {
            return $this->upload['real'];
        } else {
            //we need to strip out the extension and the '.' of the file
            //e.g 'userGuide.pdf' becomes 'userGuide'
            $baselength = strlen($this->upload['real']) - strlen($this->upload['ext']) - 1;
            $basename = substr($this->upload['real'], 0, $baselength);

            //here's the pattern we're looking for
            $pattern = '(\[)([[:digit:]]+)(\])$';

            //just incase the original filename had a sequence, we take it out 
            // e.g: 'userGuide[3]' should become 'userGuide'
            $basename = ereg_replace($pattern, '', $basename);

            /*
             * attempt to find a unique sequence file name
             */
            $i = 1;

            while (true) {
                $filename = $basename . '[' . $i . '].' . $this->upload['ext'];
                $check = $dir . DIRECTORY_SEPARATOR . $filename;
                if (!@is_file($check)) {
                    return $filename;
                }
                $i++;
            }
        }
    }

    /**
     * Unique file names in the form: 9022210413b75410c28bef.html
     * @see HTTP_Upload_File::setName()
     */
    function nameToUniq()
    {
        if (!$this->_seeded) {
            srand((double)microtime() * 1000000);
            $this->_seeded = 1;
        }
        $uniq = uniqid(rand());
        return $uniq;
    }

    /**
     * Format a file name to be safe
     *
     * @param    string $file The string file name
     * @param    int $maxlen Maximun permited string lenght
     * @return   string Formatted file name
     * @see HTTP_Upload_File::setName()
     */
    function nameToSafe($name, $maxlen = 250)
    {
        $noalpha = '�����������������������������������������������������@�������';
        $alpha = 'AEIOUYaeiouyAEIOUaeiouAEIOUaeiouAEIOUaeiouyAaOoAaNnCcaooaTtAa';

        $name = substr($name, 0, $maxlen);
        $name = strtr($name, $noalpha, $alpha);
        // not permitted chars are replaced with "_"
        return preg_replace('/[^a-zA-Z0-9,._\+\()\-]/', '_', $name);
    }

    /**
     * The upload was valid
     *
     * @return bool If the file was submitted correctly
     * @access public
     */
    function isValid()
    {
        if ($this->upload['error'] === null) {
            return true;
        }
        return false;
    }

    /**
     * User haven't submit a file
     *
     * @return bool If the user submitted a file or not
     * @access public
     */
    function isMissing()
    {
        if ($this->upload['error'] == 'NO_USER_FILE') {
            return true;
        }
        return false;
    }

    /**
     * Some error occured during upload (most common due a file size problem,
     * like max size exceeded or 0 bytes long).
     * @return bool If there were errors submitting the file (probably
     *              because the file excess the max permitted file size)
     * @access public
     */
    function isError()
    {
        if (in_array($this->upload['error'], array('TOO_LARGE', 'BAD_FORM', 'DEV_NO_DEF_FILE'))) {
            return true;
        }
        return false;
    }

    /**
     * Moves the uploaded file to its destination directory.
     *
     * @param  string $dir Destination directory
     * @param  bool $overwrite Overwrite if destination file exists?
     * @return mixed   True on success or PEAR_Error object on error
     * @access public
     */
    function moveTo($dir, $overwrite = true)
    {
        if (!$this->isValid()) {
            return $this->raiseError($this->upload['error']);
        }

        //Valid extensions check
        if (!$this->_evalValidExtensions()) {
            return $this->raiseError('NOT_ALLOWED_EXTENSION');
        }

        $err_code = $this->_chkDirDest($dir);
        if ($err_code !== false) {
            return $this->raiseError($err_code);
        }
        // Use 'safe' mode by default if no other was selected
        if (!$this->mode_name_selected) {
            $this->setName('safe');
        }

        //test to see if we're working with sequence naming mode
        if ($this->_modeNameSeq['flag'] === true) {
            $this->upload['name'] = $this->_modeNameSeq['prepend'] . $this->nameToSeq($dir) . $this->_modeNameSeq['append'];
        }

        $name = $dir . DIRECTORY_SEPARATOR . $this->upload['name'];

        if (@is_file($name)) {
            if ($overwrite !== true) {
                return $this->raiseError('FILE_EXISTS');
            } elseif (!is_writable($name)) {
                return $this->raiseError('CANNOT_OVERWRITE');
            }
        }

        // copy the file and let php clean the tmp
        if (!@move_uploaded_file($this->upload['tmp_name'], $name)) {
            return $this->raiseError('E_FAIL_MOVE');
        }
        @chmod($name, $this->_chmod);
        return $this->getProp('name');
    }

    /**
     * Check for a valid destination dir
     *
     * @param    string $dir_dest Destination dir
     * @return   mixed   False on no errors or error code on error
     */
    function _chkDirDest($dir_dest)
    {
        if (!$dir_dest) {
            return 'MISSING_DIR';
        }
        if (!@is_dir($dir_dest)) {
            return 'IS_NOT_DIR';
        }
        if (!is_writeable($dir_dest)) {
            return 'NO_WRITE_PERMS';
        }
        return false;
    }

    /**
     * Retrive properties of the uploaded file
     * @param string $name The property name. When null an assoc array with
     *                       all the properties will be returned
     * @return mixed         A string or array
     * @see HTTP_Upload_File::HTTP_Upload_File()
     * @access public
     */
    function getProp($name = null)
    {
        if ($name === null) {
            return $this->upload;
        }
        return $this->upload[$name];
    }

    /**
     * Returns a error message, if a error occured
     * (deprecated) Use getMessage() instead
     * @return string    a Error message
     * @access public
     */
    function errorMsg()
    {
        return $this->errorCode($this->upload['error']);
    }

    /**
     * Returns a error message, if a error occured
     * @return string    a Error message
     * @access public
     */
    function getMessage()
    {
        return $this->errorCode($this->upload['error']);
    }

    /**
     * Function to restrict the valid extensions on file uploads
     *
     * @param array $exts File extensions to validate
     * @param string $mode The type of validation:
     *                       1) 'deny'   Will deny only the supplied extensions
     *                       2) 'accept' Will accept only the supplied extensions
     *                                   as valid
     * @access public
     */
    function setValidExtensions($exts, $mode = 'deny')
    {
        $this->_extensionsCheck = $exts;
        $this->_extensionsMode = $mode;
    }

    /**
     * Evaluates the validity of the extensions set by setValidExtensions
     *
     * @return bool False on non valid extension, true if they are valid
     * @access private
     */
    function _evalValidExtensions()
    {
        $exts = $this->_extensionsCheck;
        settype($exts, 'array');
        if ($this->_extensionsMode == 'deny') {
            if (in_array($this->getProp('ext'), $exts)) {
                return false;
            }
            // mode == 'accept'
        } else {
            if (!in_array($this->getProp('ext'), $exts)) {
                return false;
            }
        }
        return true;
    }
}


