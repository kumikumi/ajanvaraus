<?php

require_once 'libs/common.php';
require_once 'libs/models/palvelu.php';
require_once 'libs/models/varaus.php';

$controller = new AjanvarausController();

if (!empty($_GET["varausnumero"])) {
    $controller->naytaVarausVarausnumeronPerusteella();
} else if (isset($_GET['time']) && isset($_GET['date'])) {
    $controller->naytaVarausPaivamaaranJaAjanPerusteella();
} else if (!empty($_POST["time"]) && !empty($_POST["date"]) && !empty($_POST["staff"]) && !empty($_POST["palvelu"])) {
    $controller->luoUusiVarausTaiPaivitaOlemassaOlevaa();
} else {
    header('Location: index.php');
    exit();
}

/*
 * Koska koodi olisi muuten mennyt erittäin spagetiksi, tehtiin AjanvarausController-olio. Tätä lähestymistapaa 
 * olisi voinut käyttää muihinkin kontrollereihin, jos olisi älynnyt sen aikaisemmassa vaiheessa.
 */

class AjanvarausController {

    public function __construct() {
        
    }

    /*
     * Tilanne, jossa rekisteröimätön asiakas haluaa nähdä varauksensa, ja on antanut varausnumeron get-parametrina
     */

    public function naytaVarausVarausnumeronPerusteella() {


        if (isset($_GET["date"])) {
            /*
             * Jos tuli ylimääräisiä get-parametrejä (esim. "date"), käyttäjä on submitannut formin halutessaan päivittää
             * lomakkeeseen haluamansa palvelun. Suhtaudutaan tähän siivoamalla ylimääräiset parametrit urlista pois eli
             * ohjaamalla käyttäjä tälle sivulle parametreinään varausnumero ja palvelu
             */

            header('Location: ajanvaraus.php?varausnumero=' . $_GET["varausnumero"] . "&palvelu=" . $_GET["palvelu"] . "&nimi=" . $_GET["nimi"]);
            exit();
        }

        $varaus = Varaus::etsiVarausVarausnumerolla($_GET["varausnumero"]);
        if (isset($varaus)) {

            if (isset($_GET["palvelu"])) {
                $tyontekijalista = Kayttaja::haeVapaanaOlevatTyontekijatJotkaOsaavatJaEhtivatTehdaTiettyaPalveluaTiettyynAikaan($varaus->getPvm(), $varaus->getAikaviipale(), $_GET["palvelu"]);
                if ($varaus->getPalvelu() == $_GET["palvelu"]) {
                    $tyontekijalista[] = Kayttaja::etsiTyontekija($varaus->getToimihlo());
                }
                $varaus->setPalvelu($_GET["palvelu"]);
            } else {
                $tyontekijalista = Kayttaja::haeVapaanaOlevatTyontekijatJotkaOsaavatJaEhtivatTehdaTiettyaPalveluaTiettyynAikaan($varaus->getPvm(), $varaus->getAikaviipale(), $varaus->getPalvelu());
                $tyontekijalista[] = Kayttaja::etsiTyontekija($varaus->getToimihlo());
            }

            if (isset($_GET["nimi"])) {
                $varaus->setAsiakasnimi($_GET["nimi"]);
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
            $_SESSION['virhe'] = "Syöttämälläsi varausnumerolla ei löytynyt varausta.";
            header('Location: index.php');
            exit();
        }
    }

    /*
     * Funktio palauttaa käyttäjän kysymän palvelun get-parametrin perusteella. Jos käyttäjä ei ole valinnut palvelua 
     * get-parametrina, valitaan kaikkien palveluiden listasta tietokannasta ensimmäinen palvelu.
     */

    public function maaritaKysyttyPalvelu() {
        if (isset($_GET['palvelu'])) {
            if (isset($_GET['staff'])) {
                /*
                 * Jos tuli ylimääräisiä get-parametrejä (esim. "staff"), käyttäjä on submitannut formin halutessaan päivittää
                 * lomakkeeseen haluamansa palvelun. Suhtaudutaan tähän siivoamalla ylimääräiset parametrit urlista pois eli
                 * ohjaamalla käyttäjä tälle sivulle parametreinään päivämäärä, aikaviipale ja palvelu
                 */
                header('Location: ajanvaraus.php?date=' . $_GET['date'] . "&time=" . $_GET['time'] . "&palvelu=" . $_GET['palvelu'] . "&nimi=" . $_GET["nimi"]);
                exit();
            }
            return (int) $_GET['palvelu'];
        } else {
            return Palvelu::ensimmainen()->getId();
        }
    }

    /*
     * Tilanne, jossa käyttäjä haluaa tarkastella varausta päivämäärän ja aikaviipaleen perusteella. Käyttäjä voi olla rekisteröitynyt tai rekisteröimätön asiakas
     * tai henkilökunnan jäsen. Jos käyttäjä on asiakas ja get-parametreina annettuna ajankohtana ei ole varausta, tarkoittaa tämä uuden ajanvarauksen luomista
     * eli käyttäjälle näytetään tyhjä ajanvarauslomake.
     * 
     * Jos käyttäjä on johtaja, tapahtuu jotain määrittelemätöntä.
     */

    public function naytaVarausPaivamaaranJaAjanPerusteella() {
        $time = (int) $_GET['time'];
        $date = $_GET['date'];

        if (isset($_SESSION['kayttaja']) && $_SESSION['kayttaja']->kuuluuHenkilokuntaan()) {
            $this->naytaVarausHenkilokunnanJasenelle($time, $date);
        } else {
            /*
             * Käyttäjä on asiakas (rekisteröitynyt tai rekisteröimätön)
             */
            $kysyttyPalveluId = $this->maaritaKysyttyPalvelu();
            $tyontekijat = Kayttaja::haeVapaanaOlevatTyontekijatJotkaOsaavatJaEhtivatTehdaTiettyaPalveluaTiettyynAikaan($_GET['date'], $_GET['time'], $kysyttyPalveluId);
            if (isset($_SESSION['kayttaja']) && $_SESSION['kayttaja']->onAsiakas()) {
                $this->naytaVarausNakymaKirjautuneelleAsiakkaalle($date, $time);
            } else {
                $this->naytaVarausNakymaKirjautumattomalleAsiakkaalle($date, $time, $kysyttyPalveluId, $tyontekijat);
            }
        }
    }

    /*
     * Tilanne, jossa rekisteröitynyt asiakas haluaa luoda uuden varauksen tai tarkastella olemassaolevaa varaustaan päivämäärän ja aikaviipaleen perusteella.
     */

    public function naytaVarausNakymaKirjautuneelleAsiakkaalle($date, $time) {
        $varaus = Varaus::etsiAsiakkaanVaraus($date, $time, $_SESSION['kayttaja']->getId());

        if ($varaus) {
            /*
             * Jos get-parametrina ei saatu palvelua, tai saatiin sama palvelu kuin asiakkaan varaukseen oli merkitty, 
             * lisätään varaukseen liitetty työntekijä saatavilla olevien työntekijöiden listaan. (Muussa tapauksessa
             * asiakas ei näkisi varausta tarkastellessaan itselleen varaamaansa työntekijää listassa, koska tämä
             * on varattu)
             */

            if (isset($_GET['palvelu'])) {
                $tyontekijat = Kayttaja::haeVapaanaOlevatTyontekijatJotkaOsaavatJaEhtivatTehdaTiettyaPalveluaTiettyynAikaan($varaus->getPvm(), $varaus->getAikaviipale(), $_GET["palvelu"]);
                if ($varaus->getPalvelu() == $_GET["palvelu"]) {
                    $tyontekijat[] = Kayttaja::etsiTyontekija($varaus->getToimihlo());
                }
                $varaus->setPalvelu($_GET["palvelu"]);
            } else {
                $tyontekijat = Kayttaja::haeVapaanaOlevatTyontekijatJotkaOsaavatJaEhtivatTehdaTiettyaPalveluaTiettyynAikaan($varaus->getPvm(), $varaus->getAikaviipale(), $varaus->getPalvelu());
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
            $kysyttyPalveluId = $this->maaritaKysyttyPalvelu();
            $tyontekijat = Kayttaja::haeVapaanaOlevatTyontekijatJotkaOsaavatJaEhtivatTehdaTiettyaPalveluaTiettyynAikaan($varaus->getPvm(), $varaus->getAikaviipale(), $kysyttyPalveluId);
            $uusiVaraus = new Varaus($date, $time, $kysyttyPalveluId, 0, 0, "", "");
            $uusiVaraus->setAsiakas($_SESSION['kayttaja']->getId());
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
    }

    /*
     * Tilanne, jossa kirjautumaton/rekisteröimätön asiakas haluaa tehdä uuden varauksen ajan ja päivämäärän perusteella.
     * (Rekisteröitymätön käyttäjä ei voi tarkastella olemassaolevia varauksia ajan ja päivämäärän perusteella)
     */

    public function naytaVarausNakymaKirjautumattomalleAsiakkaalle($date, $time, $kysyttyPalveluId, $tyontekijat) {
        $uusiVaraus = new Varaus($date, $time, $kysyttyPalveluId, 0, 0, "", "");
        if (isset($_GET['nimi'])) {
            $uusiVaraus->setAsiakasnimi($_GET['nimi']);
        }
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

    /*
     * Tilanne, jossa henkilökunnan jäsen tarkistelee hänelle tehtyä varausta ajan ja päivämäärän perusteella.
     */

    public function naytaVarausHenkilokunnanJasenelle($time, $date) {
        $varaus = Varaus::etsiTyontekijanVaraus($date, $time, $_SESSION['kayttaja']->getId());
        if (!isset($varaus)) {
            $_SESSION['notice'] = "Sinulle ei ole tehty varausta " . $date . " klo " . aikaviipaleTekstina($time);
            header('Location: index.php');
            exit();
        }
        $asiakas = Kayttaja::etsiKayttaja($varaus->getAsiakas());
        naytaNakyma("varauksentarkasteluview.php", array(
            "varaus" => $varaus,
            "toimihlo" => Kayttaja::etsiTyontekija($_SESSION['kayttaja']->getId()),
            "asiakas" => $asiakas,
            "palvelu" => Palvelu::etsi($varaus->getPalvelu())->getNimi()
        ));
    }

    /*
     * Tilanne jossa rekisteröitynyt tai rekisteröimätön asiakas luo uuden varauksen tai päivittää olemassaolevaa varausta post-parametrien perusteella.
     */

    public function luoUusiVarausTaiPaivitaOlemassaOlevaa() {
        if (isset($_SESSION['kayttaja']) && $_SESSION['kayttaja']->onAsiakas()) {
            $this->luoUusiRekisteroityneenAsiakkaanVarausTaiPaivitaOlemassaOlevaa();
        } else {
            $this->luoUusiRekisteroitymattomanAsiakkaanVarausTaiPaivitaOlemassaOlevaa();
        }
    }

    /*
     * Tilanne jossa rekisteröitynyt asiakas luo uuden varauksen tai päivittää olemassaolevaa varausta post-parametrien perusteella.
     */

    public function luoUusiRekisteroityneenAsiakkaanVarausTaiPaivitaOlemassaOlevaa() {
        $varaus = Varaus::etsiAsiakkaanVaraus($_POST["date"], $_POST["time"], $_SESSION['kayttaja']->getId());
        if (isset($varaus)) {
            $this->luoUusiRekisteroityneenAsiakkaanVaraus();
        } else {
            $this->paivitaOlemassaOlevaaRekisteroityneenAsiakkaanVarausta();
        }
    }

    /*
     * Tilanne jossa rekisteröitynyt asiakas luo uuden varauksen post-parametrien perusteella.
     */

    public function luoUusiRekisteroityneenAsiakkaanVaraus() {
        Varaus::paivitaRekisteroityneenAsiakkaanVaraus($_POST["date"], $_POST["time"], $_SESSION['kayttaja']->getId(), $_POST["palvelu"], $_POST["staff"]);
        $_SESSION['notice'] = "Varaustietosi on päivitetty järjestelmään.";
        header('Location: index.php');

        exit();
    }

    /*
     * Tilanne jossa rekisteröitynyt asiakas päivittää olemassaolevaa varausta post-parametrien perusteella.
     */

    public function paivitaOlemassaOlevaaRekisteroityneenAsiakkaanVarausta() {
        Varaus::uusiRekisteroityneenAsiakkaanVaraus($_POST["date"], $_POST["time"], $_POST["palvelu"], $_POST["staff"], $_SESSION['kayttaja']->getId());
        $_SESSION['notice'] = "Varauksesi on kirjattu järjestelmään.";
        header('Location: index.php');
        exit();
    }

    /*
     * Tilanne jossa rekisteröimätön asiakas luo uuden varauksen tai päivittää olemassaolevaa varausta post-parametrien perusteella.
     */

    public function luoUusiRekisteroitymattomanAsiakkaanVarausTaiPaivitaOlemassaOlevaa() {
        if (empty($_POST["varausnumero"])) {
            $this->luoUusiRekisteroitymattomanAsiakkaanVaraus();
        } else {
            $this->paivitaOlemassaOlevaaRekisteroitymattomanAsiakkaanVarausta();
        }
    }

    /*
     * Tilanne jossa rekisteröimätön asiakas luo uuden varauksen post-parametrien perusteella.
     */

    public function luoUusiRekisteroitymattomanAsiakkaanVaraus() {
        $varausnro = Varaus::annaUusiVarausnumero();
        Varaus::uusiRekisteroitymattomanAsiakkaanVaraus($_POST["date"], $_POST["time"], $_POST["palvelu"], $_POST["staff"], $_POST["nimi"], $varausnro);
        $_SESSION['notice'] = "Ajanvaraus on luotu onnistuneesti. Varausnumerosi on " . $varausnro . ", ota se talteen!";
        header('Location: index.php');
        exit();
    }

    /*
     * Tilanne jossa rekisteröimätön asiakas päivittää olemassaolevaa varausta post-parametrien perusteella.
     */

    public function paivitaOlemassaOlevaaRekisteroitymattomanAsiakkaanVarausta() {
        Varaus::paivitaVarausVarausnumeronPerusteella($_POST["varausnumero"], $_POST["palvelu"], $_POST["staff"], $_POST["nimi"]);
        $_SESSION['notice'] = "Varaustietosi on päivitetty järjestelmään. Varausnumerosi on edelleen " . $_POST["varausnumero"] . ", pidä se tallessa.";
        header('Location: index.php');
        exit();
    }

}

?>
