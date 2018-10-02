<?php
/*
 * Created on 27-ene-06
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


include_once 'Image/Graph.php';
require_once 'Classes/Manejador_Base_Datos.class.php';

class generadorGrafica
{
    private $sTipo;
    private $oGraph;
    private $iLimite;

    public function __construct($iId, $iPid, $modo, $aDatos, $aDatosExtra)
    {
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $this->sTipo = $modo;
        $this->oGraph =& Image_Graph::factory('graph', array(800, 500));
        $this->iLimite = 1000000;
        $this->oGraph->add(
            Image_Graph::vertical(
                Image_Graph::factory('title', array($aDatosExtra['titulo'], 12)),
                Image_Graph::vertical(
                    $Plotarea = Image_Graph::factory('plotarea'),
                    $Legend = Image_Graph::factory('legend'),
                    90
                ),
                5
            )
        );
        //Si hay mas de una grafica apilada mostramos la leyenda
        if (count($aDatos) > 1) {
            $Legend->setPlotarea($Plotarea);
        }
        $Font =& $this->oGraph->addNew('ttf_font', 'times');
        // set the font size to 11 pixels
        $Font->setSize(11);

        $this->oGraph->setFont($Font);
        // set the font size to 11 pixels
        $iContador = 0;
        $Dataset = array();
        foreach ($aDatos as $sFecha1 => $sValor1) {
            $Dataset[$iContador] =& Image_Graph::factory('dataset');
            foreach ($sValor1 as $sFecha => $sValor) {
                $Dataset[$iContador]->addPoint($sFecha, $sValor);
            }
            if ($this->sTipo == 4) {
                switch ($iContador) {
                    case '0':
                        {
                            $Dataset[$iContador]->setName("A�o Actual");
                            break;
                        }
                    case '1':
                        {
                            $Dataset[$iContador]->setName("A�o Anterior");
                            break;
                        }
                }
            } else {
                if ($iContador == 0) {
                    $Dataset[$iContador]->setName("A�o Actual");
                } else {
                    $Dataset[$iContador]->setName("A�o " . $sFecha1);
                }
            }
            $iContador++;
        }
        if ($modo == 'bar') {
            $Plot =& $Plotarea->addNew('bar', array($Dataset));
            $fill =& Image_Graph::factory('Image_Graph_Fill_Array');
            $fill->addNew('gradient',
                array(
                    IMAGE_GRAPH_GRAD_VERTICAL,
                    'white',
                    'red'
                )
            );
            $fill->addNew('gradient',
                array(
                    IMAGE_GRAPH_GRAD_VERTICAL,
                    'white',
                    'teal'
                )
            );
            $fill->addNew('gradient',
                array(
                    IMAGE_GRAPH_GRAD_VERTICAL,
                    'white',
                    'blue'
                )
            );
            $fill->addNew('gradient',
                array(
                    IMAGE_GRAPH_GRAD_VERTICAL,
                    'white',
                    'brown'
                )
            );
            $Plot->setFillStyle($fill);
        } else {
            $aColores = array('red', 'green', 'blue', 'purple', 'black');
            foreach ($Dataset as $iKey => $aValor) {
                $Plot[$iKey] =& $Plotarea->addNew('smooth_line', array(&$Dataset[$iKey]));
                //$Plot[$iKey]->setLineColor($aColores[$iKey]);
                $oEstiloLinea =& Image_Graph::factory('Line_Solid', $aColores[$iKey]);
                $oEstiloLinea->setThickness(2);
                $Plot[$iKey]->setLineStyle(&$oEstiloLinea);
            }
            // set a standard fill style
            //    $Plot->setFillStyle($FillArray);
        }
        $AxisX = $Plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
        $AxisX->setLabelOption('font', array('angle' => '90'));
        $AxisX->setLabelOption('position', 'inside');
        $AxisX->setTitle('Fecha');
        $AxisY = $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
        $AxisY->setTitle('Valor', 'vertical');
        $AxisY->setLabelOption('showoffset', true, 1);
        if (($aDatosExtra['valor_tolerable'] != null) && ($aDatosExtra['valor_tolerable2'] != null)) {
            if ($aDatosExtra['valor_tolerable'] > $aDatosExtra['valor_tolerable2']) {
                $sLimiteSuperior = $aDatosExtra['valor_tolerable'];
                $sLimiteInferior = $aDatosExtra['valor_tolerable2'];
            } else {
                $sLimiteSuperior = $aDatosExtra['valor_tolerable2'];
                $sLimiteInferior = $aDatosExtra['valor_tolerable'];
            }
        } else if ($aDatosExtra['valor_tolerable'] != null) {
            if ($aDatosExtra['valor_tolerable'] > $aDatosExtra['valor_objetivo']) {
                $sLimiteSuperior = $aDatosExtra['valor_tolerable'];
                $sLimiteInferior = $aDatosExtra['valor_objetivo'];
            } else {
                $sLimiteSuperior = $aDatosExtra['valor_objetivo'];
                $sLimiteInferior = $aDatosExtra['valor_tolerable'];
            }
        } else if ($aDatosExtra['valor_tolerable2'] != null) {
            if ($aDatosExtra['valor_tolerable2'] > $aDatosExtra['valor_objetivo']) {
                $sLimiteSuperior = $aDatosExtra['valor_tolerable2'];
                $sLimiteInferior = $aDatosExtra['valor_objetivo'];
            } else {
                $sLimiteSuperior = $aDatosExtra['valor_objetivo'];
                $sLimiteInferior = $aDatosExtra['valor_tolerable2'];
            }
        } else {
            $sLimiteInferior = $aDatosExtra['valor_objetivo'];
            $sLimiteSuperior = $aDatosExtra['valor_objetivo'];
        }
        $MarkerY = $Plotarea->addNew('Image_Graph_Axis_Marker_Area', null, IMAGE_GRAPH_AXIS_Y);
        $MarkerY->setFillColor('green@0.05');
        $MarkerY->setLineColor('green@0.05');
        //Ponemos los limites de lo tolerable
        $MarkerY->setLowerBound($sLimiteInferior);
        $MarkerY->setUpperBound($sLimiteSuperior);
        $MarkerY2 = $Plotarea->addNew('Image_Graph_Axis_Marker_Area', null, IMAGE_GRAPH_AXIS_Y);
        $MarkerY2->setFillColor('red@0.05');
        $MarkerY2->setLineColor('red@0.05');
        $MarkerY2->setLowerBound($sLimiteSuperior);
        $MarkerY2->setUpperBound($this->iLimite);
        if ($sLimiteInferior > 0) {
            $MarkerY3 = $Plotarea->addNew('Image_Graph_Axis_Marker_Area', null, IMAGE_GRAPH_AXIS_Y);
            $MarkerY3->setFillColor('red@0.05');
            $MarkerY3->setLineColor('red@0.05');
            $MarkerY3->setLowerBound(0);
            $MarkerY3->setUpperBound($sLimiteInferior);
        }
        $AxisY->addMark($sLimiteInferior, $sLimiteSuperior);
        $AxisY->addMark($sLimiteSuperior);
    }

    public function pintaGrafica()
    {
        $this->oGraph->done();
    }
}

