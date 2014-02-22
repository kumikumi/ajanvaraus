<?php

require_once 'config.php';
require_once 'tietokantayhteys.php';
require_once 'libs/models/kayttaja.php';
session_start();


/* Näyttää näkymätiedoston ja lähettää sille muuttujat */

function naytaNakyma($sivu, $data = array()) {
    $data = (object) $data;
    require 'views/pohja.php';
    die();
}

function nextWeekNumber($weekNumber, $year) {
    $weekNumber = intval($weekNumber);

    if ($weekNumber == viikkojaVuodessa($year)) {
        return 1;
    } else {
        return $weekNumber + 1;
    }
}

function timeStamp($vuosi, $viikkonro) {

    if ($viikkonro < 10) {
        $viikko = "0" . "$viikkonro";
    } else {
        $viikko = "$viikkonro";
    }

    return strtotime($vuosi . "W" . $viikko);
}

function viikonPaivaTekstina($viikonpv) {

    switch ($viikonpv) {
        case 1:
            return 'MA';
        case 2:
            return 'TI';
        case 3:
            return 'KE';
        case 4:
            return 'TO';
        case 5:
            return 'PE';
        case 6:
            return 'LA';
        case 7:
            return 'SU';
    }
}

function aikaviipaleTekstina($aikaviipale) {
    if ($aikaviipale % 2 == 0){
        return ($aikaviipale / 2) + 8 . ":00";
    } else {
        return (($aikaviipale - 1) / 2) + 8 . ":30";
    }
}

function varauksenViikonPv($varaus) {
    require_once 'libs/models/varaus.php';
    return viikonPaivaTekstina(date('N', strtotime($varaus->getPvm())));
}

function viikkojaVuodessa($vuosi) {
    $date = new DateTime;
    $date->setISODate($vuosi, 53);
    return ($date->format("W") === "53" ? 53 : 52);
}