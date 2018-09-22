<?php
namespace Tuqan;

// I18N support information here
$language = "es_ES";
$collate= $language.".UTF-8";
putenv("LANG=" . $language);
setlocale(LC_ALL, $language);
setlocale( LC_MESSAGES, $language);

// Set the text domain as "messages"
$domain = "qnova";
bindtextdomain($domain, "Locale");
bind_textdomain_codeset($domain, 'UTF-8');

textdomain($domain);
