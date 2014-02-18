<?php

//require_once '../tietokantayhteys.php';

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

    public static function getKayttajat() {
        $sql = "SELECT id, kayttajatunnus, salasana, kokonimi from kayttajat";
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

}
