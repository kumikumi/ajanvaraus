<?php

require_once 'libs/common.php';
require_once 'libs/models/tyovuoro.php';

if (!isset($_SESSION['kayttaja']) || !($_SESSION['kayttaja']->kuuluuHenkilokuntaan())) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['viikko'])) {
    $viikko_options = array("options" => array("min_range" => 1, "max_range" => 52));
    $valid_viikko = filter_var($_GET['viikko'], FILTER_VALIDATE_INT, $viikko_options);

    if ($valid_viikko) {
        $viikko = $valid_viikko;
    } else {
        header('Location: tyovuorot.php');
        exit();
    }
} else {
    $viikko = intval(date("W"));
}

$paiva = intval(date("N"));
$timestamp = time()+604800*(($viikko)-intval(date("W")));

$tyovuorot = Tyovuoro::haeHenkilonTyovuorot($_SESSION['kayttaja']->getId());
$taulukko = array("MA" => array(), "TI" => array(), "KE" => array(), "TO" => array(), "PE" => array(), "LA" => array(), "SU" => array());

foreach ($tyovuorot as $tyovuoro) {
    $taulukko[$tyovuoro->getViikonpv()][$tyovuoro->getAikaviipale()] = "1";
}



$viikonpaivat = array();

$viikonpaivat['MA'] = date("j.n", $timestamp+(1-$paiva)*86400);
$viikonpaivat['TI'] = date("j.n", $timestamp+(2-$paiva)*86400);
$viikonpaivat['KE'] = date("j.n", $timestamp+(3-$paiva)*86400);
$viikonpaivat['TO'] = date("j.n", $timestamp+(4-$paiva)*86400);
$viikonpaivat['PE'] = date("j.n", $timestamp+(5-$paiva)*86400);
$viikonpaivat['LA'] = date("j.n", $timestamp+(6-$paiva)*86400);
$viikonpaivat['SU'] = date("j.n", $timestamp+(7-$paiva)*86400);

naytaNakyma("tyovuoroview.php", array(
    "otsikko" => "Työvuorot",
    "taulukko" => $taulukko,
    "viikko" => $viikko,
    "viikonpaivat" => $viikonpaivat
));
?>
