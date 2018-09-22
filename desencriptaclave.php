<?php
require_once 'encriptador.php';
require_once 'etc/qnova.conf.php';
$encripta = new encriptador();
$clave = 'encriptame';
$desen = $encripta->decrypt($argv[1], $clave);
echo 'La clave es: ' . $desen . "\n";
exit();

