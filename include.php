<?php
namespace Tuqan;

// I18N support information here
$language = "es_ES";
$collate = $language . ".UTF-8";

// Use the UTF-8 variant that the Docker image actually generates.
// Bare "es_ES" does not exist in minimal Debian-based images; setlocale would fail silently.
putenv("LANG=" . $collate);
putenv("LC_ALL=" . $collate);
putenv("LC_MESSAGES=" . $collate);
setlocale(LC_ALL, $collate);
setlocale(LC_MESSAGES, $collate);

// Set the text domain as "messages"
$domain = "qnova";
bindtextdomain($domain, "Locale");
bind_textdomain_codeset($domain, 'UTF-8');

textdomain($domain);
