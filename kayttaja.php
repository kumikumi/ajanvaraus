<?php

require_once 'libs/common.php';
require_once 'libs/models/palvelu.php';

if (!isset($_SESSION['kayttaja'])) {
    $_SESSION['virhe'] = "Et ole kirjautunut sisään tai istuntosi on vanhentunut. Kirjaudu sisään ja yritä uudelleen.";
    header('Location: index.php');
    exit();
}

if (!empty($_POST['id'])) {

    if (!$_SESSION['kayttaja']->onJohtaja()) {
        header('Location: index.php');
        exit();
    }

    if (!empty($_POST['asiakas'])) {
        $asiakas = true;
    } else {
        $asiakas = false;
    }

    if (!empty($_POST['henkilokunta'])) {
        $tyontekija = true;
        
        if (!empty($_POST['t'])) {
            Palvelu::setTyontekijanPalvelut($_POST['id'], $_POST['t']);
        }
    } else {
        $tyontekija = false;
    }

    if (!empty($_POST['johtaja'])) {
        $johtaja = true;
    } else {
        $johtaja = false;
    }

    Kayttaja::muokkaaKayttajaa($_POST['id'], $_POST['nimi'], $asiakas, $tyontekija, $johtaja);
    $_SESSION['notice'] = "Muutokset tallennettu.";
    header('Location: kayttaja.php?id='.$_POST['id']);
    exit();
} else {

    if (isset($_GET['id'])) {

        if (($_GET['id'] != $_SESSION['kayttaja']->getId()) && !($_SESSION['kayttaja']->kuuluuHenkilokuntaan() || $_SESSION['kayttaja']->onJohtaja())) {
            header('Location: kayttaja.php');
            exit();
        }

        $kayttaja = Kayttaja::etsiKayttaja($_GET['id']);
    } else {
        $kayttaja = $_SESSION['kayttaja'];
    }

    $palvelut = Palvelu::getPalvelut();
    $tyontekijanPalvelut = Palvelu::getTyontekijanPalveluIdt($kayttaja->getId());

    if ($_SESSION['kayttaja']->onJohtaja()) {
        naytaNakyma("kayttajanmuokkausview.php", array(
            "otsikko" => $kayttaja->getKokonimi(),
            "kayttaja" => $kayttaja,
            "palvelut" => $palvelut,
            "tyontekijanPalvelut" => $tyontekijanPalvelut
        ));
        exit();
    } else {
        naytaNakyma("kayttajaview.php", array(
            "otsikko" => $kayttaja->getKokonimi(),
            "kayttaja" => $kayttaja,
            "palvelut" => $palvelut,
            "tyontekijanPalvelut" => $tyontekijanPalvelut
        ));
    }
}
?>
