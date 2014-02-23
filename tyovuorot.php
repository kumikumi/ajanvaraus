<?php

require_once 'libs/common.php';
require_once 'libs/models/tyovuoro.php';
require_once 'libs/kalenteri/kalenteri_funktiot.php';

if (!isset($_SESSION['kayttaja'])) {
    header('Location: index.php');
    exit();
}

if ($_SESSION['kayttaja']->kuuluuHenkilokuntaan()) {
    require 'libs/maarita_vuosi_ja_viikko.php';
    $kalenteri = muodostaTyovuoroKalenteri($_SESSION['kayttaja']->getId(), $vuosi, $viikko);

    $viikonpaivat = viikonpaivienTimestampit($vuosi, $viikko);
    naytaNakyma("tyovuoroview.php", array(
        "otsikko" => "Työvuorot",
        "viikko" => $viikko,
        "vuosi" => $vuosi,
        "kalenteri" => $kalenteri,
        "viikonpaivat" => $viikonpaivat
    ));
} else {
    header('Location: index.php');
    exit();
}
?>
