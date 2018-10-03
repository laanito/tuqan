<?php
/**
 *
 * Class created to mock HTML_Page behaviour
 *
 */

namespace Tuqan\Classes;


class FakePage
{

    /**
     * @var array
     */
    private $style;

    /**
     * @var array
     */
    private $script;

    /**
     * @var array
     */
    private $body;

    /**
     * @var string
     */
    private $title;

    public function __construct(){
        $this->style=array();
        $this->script=array();
        $this->body=array();
    }

    /**
     * @param $content string
     * @param $type string
     */
    public function addStyleDeclaration($content, $type) {
        $newStyle = array('content' => $content, 'type' => $type);
        $this->style[] = $newStyle;
    }

    /**
     * @param $content string
     * @param $type string
     */
    public function addScript($content, $type) {
        $newScript = array('content' => $content, 'type' => $type);
        $this->script[] = $newScript;
    }

    /**
     * @param $content $string
     */
    public function addBodyContent($content) {
        $this->body[]=$content;
    }

    public function display(){
        print $this->toHTML();
    }

    /**
     * @return string
     */
    public function toHTML(){
        $result = '<html>';
        $result.=$this->renderHead();
        $result.=$this->renderBody();
        $result.= '</html>';
        return $result;
    }

    /**
     * @return string
     */
    private function renderHead(){
        $result='<head>';
        $result.='<title>'.$this->title.'</title>';
        foreach($this->style as $style){
            $result.='<link rel="stylesheet" href="'.$style['content'].'" type="'.$style['type'].'" />';
        }
        foreach($this->script as $script){
            $result.='<script type="'.$script['type'].'" src="'.$script['content'].'"></script>';
        }
        $result.='</head>';
        return $result;
    }

    /**
     * @return string
     */
    private function renderBody(){
        $result='<body>';
        foreach($this->body as $content){
            $result.=$content;
        }
        $result.='</body>';
        return $result;
    }

    /**
     * @param $title
     */
    public function setTitle($title){
        $this->title=$title;
    }
}