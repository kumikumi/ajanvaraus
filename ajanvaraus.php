<?php

require_once 'libs/common.php';
require_once 'libs/models/palvelu.php';
require_once 'libs/models/varaus.php';

if (!empty($_GET["varausnumero"])) {
    if (isset($_GET["nimi"])) {
        header('Location: ajanvaraus.php?varausnumero=' . $_GET["varausnumero"] . "&palvelu=" . $_GET["palvelu"]);
        exit();
    }

    $varaus = Varaus::etsiVarausVarausnumerolla($_GET["varausnumero"]);
    if (isset($varaus)) {

        $tyontekijalista = Kayttaja::haeVapaanaOlevatTyontekijatJotkaOsaavatJaEhtivatTehdaTiettyaPalveluaTiettyynAikaan($varaus->getPvm(), $varaus->getAikaviipale(), $varaus->getPalvelu());

        if (isset($_GET["palvelu"])) {
            if ($varaus->getPalvelu() == $_GET["palvelu"]) {
                $tyontekijalista[] = Kayttaja::etsiTyontekija($varaus->getToimihlo());
            }
            $varaus->setPalvelu($_GET["palvelu"]);
        } else {
            $tyontekijalista[] = Kayttaja::etsiTyontekija($varaus->getToimihlo());
        }

        naytaNakyma("varauksenmuokkausview.php", array(
            "otsikko" => "Ajanvarauksen muokkaus",
            "exists" => 1,
            "varaus" => $varaus,
            "rekisteroitynyt" => 0,
            "palvelut" => Palvelu::getPalvelut(),
            "tyontekijat" => $tyontekijalista
        ));
    } else {
        $_SESSION['notice'] = "Syöttämälläsi varausnumerolla ei löytynyt varausta.";
        header('Location: index.php');
        exit();
    }
} else if (isset($_GET['time']) && isset($_GET['date'])) {
    $time = (int) $_GET['time'];
    $date = $_GET['date'];

    if (isset($_GET['palvelu'])) {
        if (isset($_GET['staff'])) {
            header('Location: ajanvaraus.php?date=' . $_GET['date'] . "&time=" . $_GET['time'] . "&palvelu=" . $_GET['palvelu']);
            exit();
        }
        $kysyttyPalveluId = (int) $_GET['palvelu'];
    } else {
        $kysyttyPalveluId = Palvelu::ensimmainen()->getId();
    }

    $tyontekijat = Kayttaja::haeVapaanaOlevatTyontekijatJotkaOsaavatJaEhtivatTehdaTiettyaPalveluaTiettyynAikaan($_GET['date'], $_GET['time'], $kysyttyPalveluId);
    //$tyontekijat = Kayttaja::haeTyontekijatPalvelunMukaan($kysyttyPalveluId);

    $uusiVaraus = new Varaus($date, $time, $kysyttyPalveluId, 0, 0, "", "");

    if (isset($_SESSION['kayttaja']) && $_SESSION['kayttaja']->onAsiakas()) {
        $uusiVaraus->setAsiakas($_SESSION['kayttaja']->getId());
        $varaus = Varaus::etsiAsiakkaanVaraus($date, $time, $_SESSION['kayttaja']->getId());

        if ($varaus) {
            if (isset($_GET['palvelu'])) {

                if ($varaus->getPalvelu() == $_GET["palvelu"]) {
                    $tyontekijat[] = Kayttaja::etsiTyontekija($varaus->getToimihlo());
                }

                $varaus->setPalvelu($kysyttyPalveluId);
            } else {
                $tyontekijat[] = Kayttaja::etsiTyontekija($varaus->getToimihlo());
            }
            $varaus->setAsiakasnimi($_SESSION['kayttaja']->getKokonimi());
            naytaNakyma("varauksenmuokkausview.php", array(
                "otsikko" => "Ajanvarauksen muokkaus",
                "exists" => 1,
                "varaus" => $varaus,
                "rekisteroitynyt" => 1,
                "palvelut" => Palvelu::getPalvelut(),
                "tyontekijat" => $tyontekijat
            ));
        } else {
            $uusiVaraus->setAsiakasnimi($_SESSION['kayttaja']->getKokonimi());
            naytaNakyma("varauksenmuokkausview.php", array(
                "otsikko" => "Uusi ajanvaraus",
                "exists" => 0,
                "varaus" => $uusiVaraus,
                "rekisteroitynyt" => 1,
                "palvelut" => Palvelu::getPalvelut(),
                "tyontekijat" => $tyontekijat
            ));
        }
    } else {

        naytaNakyma("varauksenmuokkausview.php", array(
            "otsikko" => "Uusi ajanvaraus",
            "varaus" => $uusiVaraus,
            "exists" => 0,
            "rekisteroitynyt" => 0,
            "ilmoitus" => "HUOM. Et ole kirjautunut sisään. Rekisteröidy kanta-asiakkaaksi tai kirjaudu sisään helpottaaksesi ajan varaamista ja saadaksesi parhaat edut.",
            "palvelut" => Palvelu::getPalvelut(),
            "tyontekijat" => $tyontekijat
        ));
    }
} else if (!empty($_POST["time"]) && !empty($_POST["date"]) && !empty($_POST["staff"]) && !empty($_POST["palvelu"])) {
    if (isset($_SESSION['kayttaja']) && $_SESSION['kayttaja']->onAsiakas()) {
        $varaus = Varaus::etsiAsiakkaanVaraus($_POST["date"], $_POST["time"], $_SESSION['kayttaja']->getId());
        if (isset($varaus)) {
            Varaus::paivitaRekisteroityneenAsiakkaanVaraus($_POST["date"], $_POST["time"], $_SESSION['kayttaja']->getId(), $_POST["palvelu"], $_POST["staff"]);
            $_SESSION['notice'] = "Varaustietosi on päivitetty järjestelmään.";
            header('Location: index.php');

            exit();
        } else {
            Varaus::uusiRekisteroityneenAsiakkaanVaraus($_POST["date"], $_POST["time"], $_POST["palvelu"], $_POST["staff"], $_SESSION['kayttaja']->getId());
            $_SESSION['notice'] = "Varauksesi on kirjattu järjestelmään.";
            header('Location: index.php');
            exit();
        }
    } else {
        if (empty($_POST["varausnumero"])) {
            $varausnro = Varaus::annaUusiVarausnumero();
            Varaus::uusiRekisteroitymattomanAsiakkaanVaraus($_POST["date"], $_POST["time"], $_POST["palvelu"], $_POST["staff"], $_POST["nimi"], $varausnro);
            $_SESSION['notice'] = "Ajanvaraus on luotu onnistuneesti. Varausnumerosi on " . $varausnro . ", ota se talteen!";
            header('Location: index.php');
            exit();
        } else {
            Varaus::paivitaVarausVarausnumeronPerusteella($_POST["varausnumero"], $_POST["palvelu"], $_POST["staff"], $_POST["nimi"]);
            $_SESSION['notice'] = "Varaustietosi on päivitetty järjestelmään. Varausnumerosi on edelleen ". $_POST["varausnumero"] . ", pidä se tallessa.";
            header('Location: index.php');
            exit();
        }
    }
} else {
    header('Location: juusto.php');
    exit();
}
?>
