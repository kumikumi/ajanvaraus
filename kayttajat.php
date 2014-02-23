<?php

require_once 'libs/common.php';
require_once 'libs/models/tyovuoro.php';
require_once 'libs/models/kayttaja.php';

if (!isset($_SESSION['kayttaja']) || !($_SESSION['kayttaja']->onJohtaja() || $_SESSION['kayttaja']->kuuluuHenkilokuntaan())) {
    $_SESSION['virhe'] = "Check your privilege!!1";
    header('Location: index.php');
    exit();
}

if (!isset($_GET['sort']) || !in_array($_GET['sort'], array("id", "tunnus", "nimi"))) {
    $sort = "id";
} else {
    $sort = $_GET['sort'];
}

function sortby_id($a, $b) {
    return $a->getId() > $b->getId();
}

function sortby_tunnus($a, $b) {
    return $a->getKayttajatunnus() > $b->getKayttajatunnus();
}

function sortby_nimi($a, $b) {
    return $a->getKokonimi() > $b->getKokonimi();
}

$kayttajat = Kayttaja::getKayttajat();

usort($kayttajat, "sortby_".$sort);

naytaNakyma("kayttajalistaview.php", array(
    "kayttajat"=>$kayttajat
));

?>
