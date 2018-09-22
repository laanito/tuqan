<?php

function file_get_contents($file)
{
    $lines = file($file);
    if ($lines) {
        return implode('', $lines);
    } else {
        return "";
    };
}

?>