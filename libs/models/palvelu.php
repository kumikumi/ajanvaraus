<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Palvelu {

    private $id;
    private $nimi;
    private $kesto;
    private $kuvaus;
    private $hinta;

    public function __construct($id, $nimi, $kesto, $kuvaus, $hinta) {
        $this->id = $id;
        $this->nimi = $nimi;
        $this->kesto = $kesto;
        $this->kuvaus = $kuvaus;
        $this->hinta = $hinta;
    }

    public function muokkaa($nimi, $kesto, $kuvaus, $hinta) {
        $this->nimi = $nimi;
        $this->kesto = $kesto;
        $this->kuvaus = $kuvaus;
        $this->hinta = $hinta;

        $sql = "UPDATE palvelu SET nimi=?, kesto=?, kuvaus=?, hinta=? where palvelu_id=?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($this->nimi, $this->kesto, $this->kuvaus, $this->hinta, $this->id));
    }

    public function getId() {
        return $this->id;
    }

    public function getNimi() {
        return $this->nimi;
    }

    public function getKesto() {
        return $this->kesto;
    }

    public function getKuvaus() {
        return $this->kuvaus;
    }

    public function getHinta() {
        return $this->hinta;
    }

    public static function getPalvelut() {
        $sql = "SELECT palvelu_id, nimi, kesto, kuvaus, hinta from palvelu";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $palvelu = new Palvelu($tulos->palvelu_id, $tulos->nimi, $tulos->kesto, $tulos->kuvaus, $tulos->hinta);
            //$array[] = $muuttuja; lisää muuttujan arrayn perään.
            //Se vastaa melko suoraan ArrayList:in add-metodia.
            $tulokset[] = $palvelu;
        }
        return $tulokset;
    }

    public static function etsi($id) {
        $sql = "SELECT palvelu_id, nimi, kesto, kuvaus, hinta from palvelu where palvelu_id = ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($id));


        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            return new Palvelu($tulos->palvelu_id, $tulos->nimi, $tulos->kesto, $tulos->kuvaus, $tulos->hinta);
        }
    }

    public static function ensimmainen() {
        $sql = "SELECT palvelu_id, nimi, kesto, kuvaus, hinta from palvelu order by palvelu_id LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();


        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            return new Palvelu($tulos->palvelu_id, $tulos->nimi, $tulos->kesto, $tulos->kuvaus, $tulos->hinta);
        }
    }

    public static function muokkaaPalvelua($id, $nimi, $kesto, $kuvaus, $hinta) {
        $sql = "UPDATE palvelu SET nimi=?, kesto=?, kuvaus=?, hinta=? where palvelu_id=?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($nimi, $kesto, $kuvaus, $hinta, $id));
    }

    public static function uusiPalvelu($nimi, $kesto, $kuvaus, $hinta) {
        $sql = "insert into palvelu (kesto, nimi, kuvaus, hinta) VALUES (?, ?, ?, ?)";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($kesto, $nimi, $kuvaus, $hinta));
    }

    public static function poistaPalvelu($id) {
        $sql = "DELETE from palvelu where palvelu_id=?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($id));
    }

}