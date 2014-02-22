<?php
require_once 'libs/common.php';
require_once 'libs/models/palvelu.php';

if (!isset($_SESSION['kayttaja']) || !($_SESSION['kayttaja']->onJohtaja())) {
    header('Location: index.php');
    exit();
}

$tyontekijat = Kayttaja::getTyontekijat();

naytaNakyma("tyontekijalistaview.php", array(
        "otsikko" => "Henkilöstö",
        "tyontekijat" => $tyontekijat
    ));

?>