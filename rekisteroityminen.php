<?php

require_once 'libs/common.php';
require_once 'libs/tietokantayhteys.php';

if (isset($_SESSION['kayttaja'])) {
    $_SESSION['virhe'] = "Mitähän järkeä tossakin nyt oli, sullahan on jo tunnus tänne";
    header('Location: index.php');
    exit();
}

if (!empty($_POST['submit'])) {
    $valid_nimi = filter_var($_POST["nimi"], FILTER_SANITIZE_STRING);
    $valid_email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $valid_tunnus = filter_var($_POST["tunnus"], FILTER_SANITIZE_STRING);
    $valid_salasana = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    $valid_salasana_uudelleen = filter_var($_POST["password2"], FILTER_SANITIZE_STRING);

    $virheet = Kayttaja::uusiAsiakas($valid_tunnus, $valid_salasana, $valid_salasana_uudelleen, $valid_nimi);

    if (!empty($virheet)) {
        naytaNakyma("views/rekview.php", array(
            "otsikko" => "Uusi käyttäjätunnus",
            "tunnus" => new Kayttaja(-1, $valid_tunnus, "", $valid_nimi),
            "virhe" => $virheet
        ));
        exit;
    } else {
        $_SESSION['notice'] = "Käyttäjätunnus luotu onnistuneesti. Voit nyt kirjoittautua sisään järjestelmään.";
        header('Location: login.php');
        exit();
    }
}

naytaNakyma("views/rekview.php", array(
    "otsikko" => "Uusi käyttäjätunnus"));
?>
