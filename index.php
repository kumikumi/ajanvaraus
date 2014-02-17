<?php

require_once 'libs/common.php';
require_once 'libs/models/tyovuoro.php';
require_once 'libs/models/varaus.php';
require_once 'libs/models/palvelu.php';

$page = "index.php";

require 'libs/maarita_vuosi_ja_viikko.php';

if (isset($_GET['palvelu']) && filter_var($_GET['palvelu'], FILTER_VALIDATE_INT)) {
    $kysyttyPalvelu = Palvelu::etsi(filter_var($_GET['palvelu'], FILTER_VALIDATE_INT));
} else {
    $kysyttyPalvelu = null;
}

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


$paivamaarat = array();

$timestamp = timeStamp(intval($vuosi), intval($viikko));

$paivamaarat['MA'] = $timestamp;
$paivamaarat['TI'] = strtotime('+1 day', $timestamp);
$paivamaarat['KE'] = strtotime('+2 day', $timestamp);
$paivamaarat['TO'] = strtotime('+3 day', $timestamp);
$paivamaarat['PE'] = strtotime('+4 day', $timestamp);
$paivamaarat['LA'] = strtotime('+5 day', $timestamp);
$paivamaarat['SU'] = strtotime('+6 day', $timestamp);

naytaNakyma("etusivuview.php", array(
    "otsikko" => "Etusivu",
    "taulukko" => $taulukko,
    "viikko" => $viikko,
    "vuosi" => $vuosi,
    "varaukset" => $varaukset,
    "paivamaarat" => $paivamaarat,
    "palvelut" => Palvelu::getPalvelut(),
    "kysyttyPalvelu" => $kysyttyPalvelu
));
?>