<?php

// include composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// create stemmer
// cukup dijalankan sekali saja, biasanya didaftarkan di service container

$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();

$dictionary = $stemmerFactory->createStemmer();

$kalimat = "Perekonomian Indonesia sedang dalam pertumbuhan yang membanggakan";
$stemmer = $dictionary->stem($kalimat);

echo $stemmer;
