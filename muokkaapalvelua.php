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
} else if (!empty($_POST["id"])) {

    if ($_POST["id"] == -1) {
        $palvelu = new Palvelu(-1, "", 1, "", 0.00);
        $otsikko = "Uusi palvelu";
    } else {
        $palvelu = Palvelu::etsi($_POST["id"]);
        $otsikko = "Palvelun muokkaus";
    }

    if (!isset($palvelu)) {
        header('Location: palvelut.php');
        exit();
    }


    $valid_id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
    $valid_nimi = filter_var($_POST["nimi"], FILTER_SANITIZE_STRING);
    $valid_kuvaus = filter_var($_POST["kuvaus"], FILTER_SANITIZE_STRING);
    $kesto_options = array("options" => array("min_range" => 1, "max_range" => 24));
    $valid_kesto = filter_var($_POST["kesto"], FILTER_VALIDATE_INT, $kesto_options);
    $valid_hinta = filter_var($_POST["hinta"], FILTER_VALIDATE_FLOAT);


    $virheet = array();

    if (empty($valid_nimi)) {
        $virheet[] = "Nimi ei saa olla tyhjä";
    }

    if (empty($valid_kuvaus)) {
        $virheet[] = "Kuvaus ei saa olla tyhjä";
    }

    if (!$valid_kesto) {
        $virheet[] = "Kesto täytyy olla kokonaisluku väliltä 1-24";
    }

    if (!$valid_hinta) {
        $virheet[] = "Hinnan täytyy olla desimaalimuodossa, desimaaliosan erottamiseen tulee käyttää pistettä esim. 10.00";
    }

    if ($valid_hinta < 0 || $valid_hinta >= 999) {
        $virheet[] = "Hinnan täytyy olla väliltä 0 - 999";
        $valid_hinta = "";
    }

    if (!empty($virheet)) {

        naytaNakyma("palvelumuokkausview.php", array(
            "otsikko" => $otsikko,
            "palvelu" => new Palvelu($valid_id, $valid_nimi, $valid_kesto, $valid_kuvaus, $valid_hinta),
            "virhe" => $virheet
        ));
        exit;
    }

    if ($_POST["id"] == -1) {
        Palvelu::uusiPalvelu($valid_nimi, $valid_kesto, $valid_kuvaus, $valid_hinta);
    } else {
        Palvelu::muokkaaPalvelua($valid_id, $valid_nimi, $valid_kesto, $valid_kuvaus, $valid_hinta);
    }
    header('Location: palvelut.php');
    exit();
} else {
    header('Location: palvelut.php');
    exit();
}
?>