<?php

require_once 'libs/common.php';
require_once 'libs/models/tyovuoro.php';

if (!isset($_SESSION['kayttaja']) || !($_SESSION['kayttaja']->kuuluuHenkilokuntaan())) {
    header('Location: index.php');
    exit();
}

$tyovuorot = Tyovuoro::haeHenkilonTyovuorot($_SESSION['kayttaja']->getId());
$taulukko = array("MA"=>array(), "TI"=>array(), "KE"=>array(), "TO"=>array(), "PE"=>array(), "LA"=> array(), "SU" => array());

foreach ($tyovuorot as $tyovuoro) {
    $taulukko[$tyovuoro->getViikonpv()][$tyovuoro->getAikaviipale()] = "1";
}

echo date("N"), "<br>";
echo date("W");
naytaNakyma("tyovuoroview.php", array(
    "otsikko" => "Työvuorot",
    "tyovuorot" => $tyovuorot,
    "taulukko" => $taulukko
));
?>
