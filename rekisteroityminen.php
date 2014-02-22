<?php

require_once 'libs/common.php';
require_once 'libs/tietokantayhteys.php';

if (isset($_SESSION['kayttaja'])) {
    header('Location: index.php');
    exit();
}

if (!empty($_POST['submit'])) {
    $valid_nimi = filter_var($_POST["nimi"], FILTER_SANITIZE_STRING);
    $valid_email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $valid_tunnus = filter_var($_POST["tunnus"], FILTER_SANITIZE_STRING);
    $valid_salasana = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    $valid_salasana_uudelleen = filter_var($_POST["password2"], FILTER_SANITIZE_STRING);

    $virheet = array();

    if (empty($valid_nimi)) {
        $virheet[] = "Nimi ei saa olla tyhjä";
    }

    if (empty($valid_email)) {
        $virheet[] = "Hei pliis anna nyt joku oikea sähköpostiosoite, ei me lähetetä spämmiä";
    }

    if (!$valid_tunnus) {
        $virheet[] = "Tarkistappa toi sun käyttäjätunnus";
    }

    if (!$valid_salasana) {
        $virheet[] = "Salasana ei saa olla tyhjä";
    }

    if (!($valid_salasana == $valid_salasana_uudelleen)) {
        $virheet[] = "Salasana ei täsmää tuohon toisella kerralla syöttämääsi salasanaan";
    }

    if (!empty($virheet)) {

        naytaNakyma("views/rekview.php", array(
            "otsikko" => "Uusi käyttäjätunnus",
            "tunnus" => new Kayttaja(-1, $valid_tunnus, "", $valid_nimi),
            "virhe" => $virheet
        ));
        exit;
    } else {
        Kayttaja::uusiAsiakas($valid_tunnus, $valid_salasana, $valid_nimi);
        $_SESSION['notice'] = "Käyttäjätunnus luotu onnistuneesti.";
        header('Location: index.php');
        exit();
    }
}

naytaNakyma("views/rekview.php", array(
    "otsikko" => "Uusi käyttäjätunnus"));
?>
