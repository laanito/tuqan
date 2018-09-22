<?php
/**
 * Created on 20-dic-2005
 *
* LICENSE see LICENSE.md file
 *
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@islanda.es</u>
 * @version 1.0b
 */

class encriptador
{

    var $r; // aleatorio o llave
    var $csl;

    /**
     * Constructor
     *
     * @access public
     * @param integer CipherSaber2 length (defaults to 1 == CS1)
     **/
    function __Construct($csl = 1) // defaults to CS1
    {
        $this->csl = $csl;
    }

    /**
     * set CS2 length
     *
     * @access public
     * @param $length - integer length value
     **/
    function setCsLength($length)
    {
        $this->csl = $length;
    }

    /**
     * get CS2 length
     *
     * @access public
     * @return integer length value
     **/
    function getCsLength()
    {
        return $this->csl;
    }

    /**
     * Usual string encrypt with additional base64 encryption
     *
     * @access public
     * @param $str - string to be encrypted
     * @param $key - key/password to be used for encryption
     * @return string encrypted and base64 encoded string
     **/
    function encrypt($str, $key)
    {
        srand((double)microtime() * 1234567);
        $this->r = substr(md5(rand(0, 32000)), 0, 10);
        return base64_encode($this->r . $this->_cs($str, $key));
    }

    /**
     * Usual base64 string decrypt
     *
     * @access public
     * @param $str - string (base64 encoded) to be decrypted
     * @param $key - key/password to be used for decryption
     * @return string decrypted string
     **/
    function decrypt($str, $key)
    {
        $str = base64_decode($str);
        $this->r = substr($str, 0, 10);
        $str = substr($str, 10);
        return $this->_cs($str, $key);
    }

    /**
     * Encrypt without base64
     *
     * @access public
     * @param $str - string to be encrypted
     * @param $key - key/password to be used for encryption
     * @return string encrypted
     **/
    function binEncrypt($str, $key)
    {
        srand((double)microtime() * 1234567);
        $this->r = substr(md5(rand(0, 32000)), 0, 10);
        return $this->r . $this->_cs($str, $key);
    }

    /**
     * Decrypt without base64
     *
     * @access public
     * @param $str - string to be decrypted
     * @param $key - key/password to be used for decryption
     * @return string decrypted
     **/
    function binDecrypt($str, $key)
    {
        $this->r = substr($str, 0, 10);
        $str = substr($str, 10);
        return $this->_cs($str, $key);
    }


    /**
     * CipherSaber algorithm
     *
     * @access private
     * @param $d - string
     * @param $p - key
     * @return string encrypted/decrypted
     **/
    function _cs($d, $p)
    {
        $k = $this->r;
        $p .= $k;

        for ($i = 0; $i < 256; $i++)
            $S[$i] = $i;

        $j = 0;
        $t = strlen($p);

        for ($i = 0; $i < 256; $i++) {
            $K[$i] = ord(substr($p, $j, 1));
            $j = ($j + 1) % $t;
        }

        $j = 0;
        for ($kk = 0; $kk < $this->csl; $kk++) // this loop gives CS2 functionality
        {
            for ($i = 0; $i < 256; $i++) {
                $j = ($j + $S[$i] + $K[$i]) & 0xff;
                $t = $S[$i];
                $S[$i] = $S[$j];
                $S[$j] = $t;
            }
        }

        $i = 0;
        $j = 0;
        $ret = '';

        $dlen = strlen($d);
        for ($ii = 0; $ii < $dlen; $ii++) {
            $c = $d{$ii};
            $i = ($i + 1) & 0xff;
            $j = ($j + $S[$i]) & 0xff;
            $t = $S[$i];
            $S[$i] = $S[$j];
            $S[$j] = $t;
            $t = ($S[$i] + $S[$j]) & 0xff;
            $ret .= chr($S[$t]) ^ $c;
        }

        return $ret;
    }
}
