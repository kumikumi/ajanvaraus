<?php

require_once 'libs/common.php';
require_once 'libs/models/tyovuoro.php';
require_once 'libs/models/varaus.php';

require 'libs/maarita_vuosi_ja_viikko.php';

$taulukko = array("MA" => array(), "TI" => array(), "KE" => array(), "TO" => array(), "PE" => array(), "LA" => array(), "SU" => array());
$tyovuorot = Tyovuoro::haeTyovuorot($vuosi, $viikko);

foreach ($tyovuorot as $tyovuoro) {
    if (!isset($taulukko[$tyovuoro->getViikonpv()][$tyovuoro->getAikaviipale()])) {
        $taulukko[$tyovuoro->getViikonpv()][$tyovuoro->getAikaviipale()] = array();
    }
    $taulukko[$tyovuoro->getViikonpv()][$tyovuoro->getAikaviipale()][$tyovuoro->getTyontekija_id()] = "available";
}

$varaukset = Varaus::haeVarauksetViikolle($vuosi, $viikko);

foreach ($varaukset as $varaus) {
    if (isset($taulukko[varauksenViikonPv($varaus)][$varaus->getAikaviipale()])&& isset($taulukko[varauksenViikonPv($varaus)][$varaus->getAikaviipale()][$varaus->getToimihlo()])){
        $taulukko[varauksenViikonPv($varaus)][$varaus->getAikaviipale()][$varaus->getToimihlo()]="taken";
    }
}


$viikonpaivat = array();

$timestamp = timeStamp(intval($vuosi), intval($viikko));

$viikonpaivat['MA'] = date("j.n", $timestamp);
$viikonpaivat['TI'] = date("j.n", strtotime('+1 day', $timestamp));
$viikonpaivat['KE'] = date("j.n", strtotime('+2 day', $timestamp));
$viikonpaivat['TO'] = date("j.n", strtotime('+3 day', $timestamp));
$viikonpaivat['PE'] = date("j.n", strtotime('+4 day', $timestamp));
$viikonpaivat['LA'] = date("j.n", strtotime('+5 day', $timestamp));
$viikonpaivat['SU'] = date("j.n", strtotime('+6 day', $timestamp));


naytaNakyma("etusivuview.php", array(
    "otsikko" => "Etusivu",
    "taulukko" => $taulukko,
    "viikonpaivat" => $viikonpaivat,
    "viikko" => $viikko,
    "vuosi" => $vuosi,
    "varaukset" => $varaukset
));
?>