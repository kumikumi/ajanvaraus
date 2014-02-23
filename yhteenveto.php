<?php
require_once 'libs/common.php';

if (!isset($_SESSION['kayttaja']) || !($_SESSION['kayttaja']->onJohtaja())) {
    header('Location: index.php');
    exit();
}

$kayttajaLkm = Kayttaja::kayttajaLkm();
$henkilokuntaLkm = Kayttaja::henkilokuntaLkm();
$asiakasLkm = Kayttaja::asiakasLkm();

naytaNakyma("yhteenvetoview.php", array(
    "otsikko" => "Yhteenveto",
    "nimi" => $_SESSION['kayttaja']->getKokonimi(),
    "kayttajaLkm" => $kayttajaLkm,
    "henkilokuntaLkm" => $henkilokuntaLkm,
    "asiakasLkm" => $asiakasLkm
));

?>