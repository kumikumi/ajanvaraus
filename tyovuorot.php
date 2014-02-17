<?php

require_once 'libs/common.php';
require_once 'libs/models/tyovuoro.php';

if (!isset($_SESSION['kayttaja']) || !($_SESSION['kayttaja']->kuuluuHenkilokuntaan())) {
    header('Location: index.php');
    exit();
}
$page = "tyovuorot.php";
require 'libs/maarita_vuosi_ja_viikko.php';

$tyovuorot = Tyovuoro::haeHenkilonTyovuorot($_SESSION['kayttaja']->getId());
$taulukko = array("MA" => array(), "TI" => array(), "KE" => array(), "TO" => array(), "PE" => array(), "LA" => array(), "SU" => array());

foreach ($tyovuorot as $tyovuoro) {
    $taulukko[$tyovuoro->getViikonpv()][$tyovuoro->getAikaviipale()] = "1";
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
naytaNakyma("tyovuoroview.php", array(
    "otsikko" => "Työvuorot",
    "taulukko" => $taulukko,
    "viikko" => $viikko,
    "vuosi" => $vuosi,
    "viikonpaivat" => $viikonpaivat
));
?>
