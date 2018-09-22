<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/html2ps.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

// Works only with safe mode off; in safe mode it generates a warning message
@set_time_limit(600);

require_once('pipeline.factory.class.php');

check_requirements();

$g_baseurl = trim($_REQUEST['URL']);

$sLogin = trim($_REQUEST['login']);
$sPass = trim($_REQUEST['pass']);
$sDb = trim($_REQUEST['db']);
$sDoc = trim($_REQUEST['doc']);

/*$g_baseurl = trim($_SERVER['argv'][1]);

$sLogin = trim($_SERVER['argv'][2]);
$sPass = trim($_SERVER['argv'][3]);
$sDb = trim($_SERVER['argv'][4]);
$sDoc = trim($_SERVER['argv'][5]);*/


if ($g_baseurl === "") {
    die("Please specify URL to process!");
}

// Add HTTP protocol if none specified
if (!preg_match("/^https?:/", $g_baseurl)) {
    $g_baseurl = 'http://' . $g_baseurl;
}

$g_css_index = 0;

// Title of styleshee to use (empty if no preferences are set)
$g_stylesheet_title = "";

$g_config = array(
    'cssmedia' => isset($_REQUEST['cssmedia']) ? $_REQUEST['cssmedia'] : "screen",
    'convert' => isset($_REQUEST['convert']),
    'media' => isset($_REQUEST['media']) ? $_REQUEST['media'] : "A4",
    'scalepoints' => isset($_REQUEST['scalepoints']),
    'renderimages' => isset($_REQUEST['renderimages']),
    'renderfields' => isset($_REQUEST['renderfields']),
    'renderforms' => isset($_REQUEST['renderforms']),
    'pslevel' => isset($_REQUEST['pslevel']) ? $_REQUEST['pslevel'] : 2,
    'renderlinks' => isset($_REQUEST['renderlinks']),
    'pagewidth' => isset($_REQUEST['pixels']) ? (int)$_REQUEST['pixels'] : 800,
    'landscape' => isset($_REQUEST['landscape']),
    'method' => isset($_REQUEST['method']) ? $_REQUEST['method'] : "fpdf",
    'margins' => array(
        'left' => isset($_REQUEST['leftmargin']) ? (int)$_REQUEST['leftmargin'] : 0,
        'right' => isset($_REQUEST['rightmargin']) ? (int)$_REQUEST['rightmargin'] : 0,
        'top' => isset($_REQUEST['topmargin']) ? (int)$_REQUEST['topmargin'] : 0,
        'bottom' => isset($_REQUEST['bottommargin']) ? (int)$_REQUEST['bottommargin'] : 0
    ),
    'encoding' => isset($_REQUEST['encoding']) ? $_REQUEST['encoding'] : "",
    'ps2pdf' => isset($_REQUEST['ps2pdf']) ? $_REQUEST['ps2pdf'] : 0,
    'compress' => isset($_REQUEST['compress']) ? $_REQUEST['compress'] : 0,
    'output' => isset($_REQUEST['output']) ? $_REQUEST['output'] : 0,
    'pdfversion' => isset($_REQUEST['pdfversion']) ? $_REQUEST['pdfversion'] : "1.2",
    'transparency_workaround' => isset($_REQUEST['transparency_workaround']),
    'imagequality_workaround' => isset($_REQUEST['imagequality_workaround']),
    'draw_page_border' => isset($_REQUEST['pageborder']),
    'debugbox' => isset($_REQUEST['debugbox']),
    'html2xhtml' => !isset($_REQUEST['html2xhtml']),
    'mode' => 'html'
);

// ========== Entry point
parse_config_file('./.html2ps.config');
//parse_config_file('public_html/.html2ps.config');

// validate input data
if ($g_config['pagewidth'] == 0) {
    die("Please specify non-zero value for the pixel width!");
};

// begin processing

$g_media = Media::predefined($g_config['media']);
$g_media->set_landscape($g_config['landscape']);
$g_media->set_margins($g_config['margins']);
$g_media->set_pixels($g_config['pagewidth']);

$g_px_scale = mm2pt($g_media->width() - $g_media->margins['left'] - $g_media->margins['right']) / $g_media->pixels;
if ($g_config['scalepoints']) {
    $g_pt_scale = $g_px_scale * 1.43; // This is a magic number, just don't touch it, or everything will explode!
} else {
    $g_pt_scale = 1.0;
};

// Initialize the coversion pipeline
//$pipeline = new Pipeline($sLogin,$sPass,$sDb);
$pipeline = new Pipeline();

// Configure the fetchers
$pipeline->fetchers[] = new FetcherURL();

// Configure the data filters
$pipeline->data_filters[] = new DataFilterDoctype();
$pipeline->data_filters[] = new DataFilterUTF8($g_config['encoding']);
if ($g_config['html2xhtml']) {
    $pipeline->data_filters[] = new DataFilterHTML2XHTML();
} else {
    $pipeline->data_filters[] = new DataFilterXHTML2XHTML();
};

$pipeline->parser = new ParserXHTML();

$pipeline->pre_tree_filters = array();
if ($g_config['renderfields']) {
    $pipeline->pre_tree_filters[] = new PreTreeFilterHTML2PSFields("", "", "");
};

if ($g_config['method'] === 'ps') {
    $pipeline->layout_engine = new LayoutEnginePS();
} else {
    $pipeline->layout_engine = new LayoutEngineDefault();
};

$pipeline->post_tree_filters = array();

// Configure the output format
if ($g_config['pslevel'] == 3) {
    $image_encoder = new PSL3ImageEncoderStream();
} else {
    $image_encoder = new PSL2ImageEncoderStream();
};

switch ($g_config['method']) {
    case 'ps':
        $pipeline->output_driver = new OutputDriverPS($g_config['scalepoints'],
            $g_config['transparency_workaround'],
            $g_config['imagequality_workaround'],
            $image_encoder);
        break;
    case 'fastps':
        $pipeline->output_driver = new OutputDriverFastPS($image_encoder);
        break;
    case 'pdflib':
        $pipeline->output_driver = new OutputDriverPDFLIB($g_config['pdfversion']);
        break;
    case 'fpdf':
        //Aqui ademas le pasamos como datos los parametros de conexion (tenia problemas para obtenerlos por sesion)
        $pipeline->output_driver = new OutputDriverFPDF();
        $pipeline->output_driver->login = $sLogin;
        $pipeline->output_driver->pass = $sPass;
        $pipeline->output_driver->db = $sDb;
        $pipeline->output_driver->doc = $sDoc;
        break;
    default:
        die("Unknown output method");
};

if ($g_config['debugbox']) {
    $pipeline->output_driver->set_debug_boxes(true);
}

if ($g_config['draw_page_border']) {
    $pipeline->output_driver->set_show_page_border(true);
}

if ($g_config['ps2pdf']) {
    $pipeline->output_filters[] = new OutputFilterPS2PDF($g_config['pdfversion']);
}

if ($g_config['compress']) {
    $pipeline->output_filters[] = new OutputFilterGZip();
}

switch ($g_config['output']) {
    case 0:
        $pipeline->destination = new DestinationBrowser($g_baseurl);
        break;
    case 1:
        $pipeline->destination = new DestinationDownload($g_baseurl);
        break;
    case 2:
        $pipeline->destination = new DestinationFile($g_baseurl);
        break;
};

// Start the conversion

$status = $pipeline->process($g_baseurl, $g_media);
if ($status == null) {
    print($pipeline->error_message());
    error_log("Error in conversion pipeline");
    die();
}

?>