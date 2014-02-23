<?php

require_once 'libs/common.php';

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
    header('Location: kayttajat.php');
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

    if ($_SESSION['kayttaja']->onJohtaja()) {
        naytaNakyma("kayttajanmuokkausview.php", array(
            "otsikko" => $kayttaja->getKokonimi(),
            "kayttaja" => $kayttaja
        ));
        exit();
    } else {
        naytaNakyma("kayttajaview.php", array(
            "otsikko" => $kayttaja->getKokonimi(),
            "kayttaja" => $kayttaja
        ));
    }
}
?>
