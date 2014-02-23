<?php

class Kayttaja {

    private $id;
    private $kayttajatunnus;
    private $salasana;
    private $kokonimi;
    private $asiakas;
    private $henkilokunta;
    private $johto;
    private $saldo;

    public function __construct($id, $kayttajatunnus, $salasana, $kokonimi) {
        $this->id = $id;
        $this->kayttajatunnus = $kayttajatunnus;
        $this->salasana = $salasana;
        $this->kokonimi = $kokonimi;
    }

    public function getId() {
        return $this->id;
    }

    public function getKayttajatunnus() {
        return $this->kayttajatunnus;
    }

    public function getSalasana() {
        return $this->salasana;
    }

    public function getKokonimi() {
        return $this->kokonimi;
    }

    public function kuuluuHenkilokuntaan() {
        return $this->henkilokunta;
    }

    public function onAsiakas() {
        return $this->asiakas;
    }

    public function onJohtaja() {
        return $this->johto;
    }

    public function getSaldo() {
        return $this->saldo;
    }

    /* Tähän gettereitä ja settereitä */

    public static function getKayttajaTunnuksilla($kayttaja, $salasana) {
        $sql = "SELECT id,kayttajatunnus, salasana, kokonimi from kayttajat where kayttajatunnus = ? AND salasana = ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($kayttaja, $salasana));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            $kayttaja = new Kayttaja();
            $kayttaja->id = $tulos->id;
            $kayttaja->kayttajatunnus = $tulos->kayttajatunnus;
            $kayttaja->kokonimi = $tulos->kokonimi;
            $kayttaja->henkilokunta = Kayttaja::kuuluukoHenkilokuntaan($kayttaja->id);
            $kayttaja->asiakas = Kayttaja::kuuluukoAsiakaskuntaan($kayttaja->id);
            $kayttaja->johto = Kayttaja::kuuluukoJohtoryhmaan($kayttaja->id);

            if ($kayttaja->asiakas) {
                $kayttaja->saldo = Kayttaja::haeAsiakasSaldo($kayttaja->id);
            }

            return $kayttaja;
        }
    }

    public static function kayttajatJarjestettyna($sort) {
        if ($sort === "id") {
            $sql = "SELECT id, kayttajatunnus, salasana, kokonimi from kayttajat order by id";
        } else if ($sort === "tunnus") {
            $sql = "SELECT id, kayttajatunnus, salasana, kokonimi from kayttajat order by tunnus";
        } else if ($sort === "nimi") {
            $sql = "SELECT id, kayttajatunnus, salasana, kokonimi from kayttajat order by kokonimi";
        }

        echo $sql;
        $sql = "SELECT id, kayttajatunnus, salasana, kokonimi from kayttajat";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $kayttaja = new Kayttaja($tulos->id, $tulos->kayttajatunnus, $tulos->salasana, $tulos->kokonimi);
            $kayttaja->henkilokunta = Kayttaja::kuuluukoHenkilokuntaan($kayttaja->id);
            $kayttaja->asiakas = Kayttaja::kuuluukoAsiakaskuntaan($kayttaja->id);
            $kayttaja->johto = Kayttaja::kuuluukoJohtoryhmaan($kayttaja->id);
            //$array[] = $muuttuja; lisää muuttujan arrayn perään.
            //Se vastaa melko suoraan ArrayList:in add-metodia.
            $tulokset[] = $kayttaja;
        }
        return $tulokset;
    }

    public static function getKayttajat() {
        $sql = "SELECT id, kayttajatunnus, salasana, kokonimi from kayttajat";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $kayttaja = new Kayttaja($tulos->id, $tulos->kayttajatunnus, $tulos->salasana, $tulos->kokonimi);
            $kayttaja->henkilokunta = Kayttaja::kuuluukoHenkilokuntaan($kayttaja->id);
            $kayttaja->asiakas = Kayttaja::kuuluukoAsiakaskuntaan($kayttaja->id);
            $kayttaja->johto = Kayttaja::kuuluukoJohtoryhmaan($kayttaja->id);
            //$array[] = $muuttuja; lisää muuttujan arrayn perään.
            //Se vastaa melko suoraan ArrayList:in add-metodia.
            $tulokset[] = $kayttaja;
        }
        return $tulokset;
    }

    public static function getTyontekijat() {
        $sql = "SELECT id, kayttajatunnus, salasana, kokonimi from kayttajat, henkilokunta where id = hlo_id";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $kayttaja = new Kayttaja($tulos->id, $tulos->kayttajatunnus, $tulos->salasana, $tulos->kokonimi);
            //$array[] = $muuttuja; lisää muuttujan arrayn perään.
            //Se vastaa melko suoraan ArrayList:in add-metodia.
            $tulokset[] = $kayttaja;
        }
        return $tulokset;
    }

    private static function kuuluukoHenkilokuntaan($kayttajaid) {
        $sql = "SELECT hlo_id from henkilokunta where hlo_id = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($kayttajaid));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    private static function kuuluukoAsiakaskuntaan($kayttajaid) {
        $sql = "SELECT asiakas_id from asiakas where asiakas_id = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($kayttajaid));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    private static function kuuluukoJohtoryhmaan($kayttajaid) {
        $sql = "SELECT joh_id from johto where joh_id = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($kayttajaid));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    private static function haeAsiakasSaldo($kayttajaid) {
        $sql = "SELECT asiakastili from asiakas where asiakas_id = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($kayttajaid));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            return $tulos->asiakastili;
        }
    }

    public static function etsiTyontekija($kayttajaid) {
        $sql = "SELECT id,kayttajatunnus, salasana, kokonimi from kayttajat, henkilokunta where id = ? AND hlo_id = id LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($kayttajaid));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            $kayttaja = new Kayttaja($tulos->id, $tulos->kayttajatunnus, $tulos->salasana, $tulos->kokonimi);
            $kayttaja->henkilokunta = Kayttaja::kuuluukoHenkilokuntaan($kayttaja->id);
            $kayttaja->asiakas = Kayttaja::kuuluukoAsiakaskuntaan($kayttaja->id);
            $kayttaja->johto = Kayttaja::kuuluukoJohtoryhmaan($kayttaja->id);

            if ($kayttaja->asiakas) {
                $kayttaja->saldo = Kayttaja::haeAsiakasSaldo($kayttaja->id);
            }

            return $kayttaja;
        }
    }

    public static function etsiKayttaja($kayttajaid) {
        $sql = "SELECT id,kayttajatunnus, salasana, kokonimi from kayttajat where id = ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($kayttajaid));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            $kayttaja = new Kayttaja($tulos->id, $tulos->kayttajatunnus, $tulos->salasana, $tulos->kokonimi);
            $kayttaja->henkilokunta = Kayttaja::kuuluukoHenkilokuntaan($kayttaja->id);
            $kayttaja->asiakas = Kayttaja::kuuluukoAsiakaskuntaan($kayttaja->id);
            if ($kayttaja->asiakas) {
                $kayttaja->saldo = Kayttaja::haeAsiakasSaldo($kayttaja->id);
            }
            $kayttaja->johto = Kayttaja::kuuluukoJohtoryhmaan($kayttaja->id);

            return $kayttaja;
        }
    }

    public static function onkoTunnusVarattu($kayttajatunnus) {
        $sql = "SELECT count(*) from kayttajat where kayttajatunnus like ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($kayttajatunnus));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            return $tulos->count;
        }
    }

    public static function haeTyontekijatPalvelunMukaan($palvelu_id) {
        $sql = "SELECT id, kayttajatunnus, salasana, kokonimi from kayttajat, hlokpalvelut where kayttajat.id = hlokpalvelut.hlo_id and palvelu_id = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($palvelu_id));

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $kayttaja = new Kayttaja($tulos->id, $tulos->kayttajatunnus, $tulos->salasana, $tulos->kokonimi);
            //$array[] = $muuttuja; lisää muuttujan arrayn perään.
            //Se vastaa melko suoraan ArrayList:in add-metodia.
            $tulokset[] = $kayttaja;
        }
        return $tulokset;
    }

    public static function haeVapaanaOlevatTyontekijatJotkaOsaavatJaEhtivatTehdaTiettyaPalveluaTiettyynAikaan($pvm, $aikaviipale, $palvelu_id) {
        require_once 'libs/kalenteri/kalenteri_funktiot.php';
        require_once 'libs/models/tyovuoro.php';
        require_once 'libs/models/palvelu.php';
        $time = strtotime($pvm);
        $vuosi = (int)date("o", $time);
        $viikko = (int)date("W", $time);
        $viikonpv = date("N", $time);

        $palautus = array();
        $taulukko = muodostaTaulukko(Palvelu::etsi($palvelu_id), null, $vuosi, $viikko);
        if (empty($taulukko[viikonPaivaTekstina($viikonpv)][$aikaviipale])) {
            return array();
        }
        try {
            foreach ($taulukko[viikonPaivaTekstina($viikonpv)][$aikaviipale] as $tyontekijaid => $tila) {
                if ($tila == "available") {
                    $palautus[] = Kayttaja::etsiTyontekija($tyontekijaid);
                }
            }
        } catch (Exception $e) {
            return array();
        }
        return $palautus;
    }

    public static function uusiAsiakas($valid_tunnus, $valid_salasana, $valid_salasana_uudelleen, $valid_nimi) {
        $virheet = array();

        if (empty($valid_nimi)) {
            $virheet[] = "Nimi ei saa olla tyhjä";
        }
        
//        if (empty($valid_email)) {
//            $virheet[] = "Hei pliis anna nyt joku oikea sähköpostiosoite, ei me lähetetä spämmiä";
//        }

        if (!$valid_tunnus) {
            $virheet[] = "Tarkistappa toi sun käyttäjätunnus";
        } else {
            if (Kayttaja::onkoTunnusVarattu($valid_tunnus)) {
                $virheet[] = "Käyttäjätunnus on jo varattu";
            }
        }

        if (!$valid_salasana) {
            $virheet[] = "Salasana ei saa olla tyhjä";
        }

        if (!($valid_salasana == $valid_salasana_uudelleen)) {
            $virheet[] = "Salasana ei täsmää tuohon toisella kerralla syöttämääsi salasanaan";
        }

        if (!empty($virheet)) {
            return $virheet;
        } else {
            $sql = "insert into kayttajat (kayttajatunnus, salasana, kokonimi) VALUES (?, ?, ?)";
            $kysely = getTietokantayhteys()->prepare($sql);
            $kysely->execute(array($valid_tunnus, $valid_salasana, $valid_nimi));

            $sql2 = "insert into asiakas (asiakas_id) select id from kayttajat where kayttajatunnus like ? and salasana like ? and kokonimi like ?";
            $kysely2 = getTietokantayhteys()->prepare($sql2);
            $kysely2->execute(array($valid_tunnus, $valid_salasana, $valid_nimi));
        }
    }

    public static function muokkaaKayttajaa($id, $kokonimi, $asiakas, $tyontekija, $johtaja) {
        $sql = "UPDATE kayttajat SET kokonimi=? where id=?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($kokonimi, $id));

        $edellinen = Kayttaja::etsiKayttaja($id);

        if ($edellinen->onAsiakas() != $asiakas) {

            if ($asiakas) {
                $sql2 = "insert into asiakas (asiakas_id) VALUES (?)";
            } else {
                $sql2 = "delete from asiakas where asiakas_id = ?";
            }
            echo $sql2 . "<br>";
            $kysely2 = getTietokantayhteys()->prepare($sql2);
            $kysely2->execute(array($id));
        }

        if ($edellinen->kuuluuHenkilokuntaan() != $tyontekija) {
            if ($tyontekija) {
                $sql3 = "insert into henkilokunta (hlo_id) VALUES (?)";
            } else {
                $sql3 = "delete from henkilokunta where hlo_id = ?";
            }
            echo $sql3 . "<br>";
            $kysely3 = getTietokantayhteys()->prepare($sql3);
            $kysely3->execute(array($id));
        }

        if ($edellinen->onJohtaja() != $johtaja) {

            if ($johtaja) {
                $sql4 = "insert into johto (joh_id) VALUES (?)";
            } else {
                $sql4 = "delete from johto where joh_id = ?";
            }
            echo $sql4 . "<br>";
            $kysely4 = getTietokantayhteys()->prepare($sql4);
            $kysely4->execute(array($id));
        }
    }

    public static function asiakasLkm() {
        $sql = "SELECT count(*) from asiakas";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            return $tulos->count;
        }
    }

    public static function kayttajaLkm() {
        $sql = "SELECT count(*) from kayttajat";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            return $tulos->count;
        }
    }

    public static function henkilokuntaLkm() {
        $sql = "SELECT count(*) from henkilokunta";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            return $tulos->count;
        }
    }
}