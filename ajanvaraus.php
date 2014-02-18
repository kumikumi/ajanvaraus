<?php

require_once 'libs/common.php';
require_once 'libs/models/palvelu.php';
require_once 'libs/models/varaus.php';

if (isset($_GET['time']) && isset($_GET['date'])) {
    $time = (int) $_GET['time'];
    $date = $_GET['date'];

    if (isset($_GET['palvelu'])) {
        $kysyttyPalveluId = (int) $_GET['palvelu'];
    } else {
        $kysyttyPalveluId = null;
    }

    naytaNakyma("varauksenmuokkausview.php", array(
        "otsikko" => "Ajanvaraus",
        "kysyttyPalveluId" => $kysyttyPalveluId,
        "palvelut" => Palvelu::getPalvelut(),
        "tyontekijat" => Kayttaja::getTyontekijat(),
        "time" => $time,
        "date" => $date
    ));
} else if (!empty($_POST["time"]) && !empty($_POST["date"]) && !empty($_POST["staff"]) && !empty($_POST["palvelu_id"])) {
    if (isset($_SESSION['kayttaja']) && $_SESSION['kayttaja']->onAsiakas()) {
        Varaus::uusiVaraus($_POST["date"], $_POST["time"], $_POST["palvelu_id"], $_POST["staff"], $_SESSION['kayttaja']->getId());
    } else {
        header('Location: palvelut.php');
        exit();
    }
} else {
    header('Location: palvelut.php');
    exit();
}
?>
