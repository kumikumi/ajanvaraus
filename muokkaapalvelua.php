<?php

require_once 'libs/common.php';
require_once 'libs/models/palvelu.php';

if (!isset($_SESSION['kayttaja']) || !($_SESSION['kayttaja']->kuuluuHenkilokuntaan())) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    if ($id == -1) {
        $palvelu = new Palvelu(-1, "", 1, "", 0.00);
        $otsikko = "Uusi palvelu";
    } else {
        $palvelu = Palvelu::etsi($id);
        $otsikko = "Palvelun muokkaus";
    }

    if (!isset($palvelu)) {
        header('Location: palvelut.php');
        exit();
    }

    naytaNakyma("palvelumuokkausview.php", array(
        "otsikko" => $otsikko,
        "palvelu" => $palvelu
    ));
    exit;
} else if (!empty($_POST["id"]) && !empty($_POST["nimi"]) && !empty($_POST["kuvaus"]) && !empty($_POST["kesto"]) && !empty($_POST["hinta"])) {

    if ($_POST["id"] == -1) {
        Palvelu::uusiPalvelu($_POST["nimi"], $_POST["kesto"], $_POST["kuvaus"], $_POST["hinta"]);
    } else {
        Palvelu::muokkaaPalvelua($_POST["id"], $_POST["nimi"], $_POST["kesto"], $_POST["kuvaus"], $_POST["hinta"]);
    }
    header('Location: palvelut.php');
    exit();
} else {
    header('Location: palvelut.php');
    exit();
}
?>