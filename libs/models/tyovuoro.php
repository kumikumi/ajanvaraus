<?php

class Tyovuoro {

    private $tyontekija_id;
    private $viikonpv;
    private $aikaviipale;

    public function __construct($tyontekija_id, $viikonpv, $aikaviipale) {
        $this->tyontekija_id = $tyontekija_id;
        $this->viikonpv = $viikonpv;
        $this->aikaviipale = $aikaviipale;
    }

    public function getTyontekija_id() {
        return $this->tyontekija_id;
    }

    public function getViikonpv() {
        return $this->viikonpv;
    }

    public function getAikaviipale() {
        return $this->aikaviipale;
    }

    public static function haeHenkilonTyovuorot($tyontekija_id) {
        $sql = "SELECT hlo_id, paiva, aikaviipale from tyovuoro where hlo_id = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($tyontekija_id));

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $tyovuoro = new Tyovuoro($tulos->hlo_id, $tulos->paiva, $tulos->aikaviipale);
            //$array[] = $muuttuja; lisää muuttujan arrayn perään.
            //Se vastaa melko suoraan ArrayList:in add-metodia.
            $tulokset[] = $tyovuoro;
        }
        return $tulokset;
    }

    public static function haeTyovuorot() {
        $sql = "SELECT hlo_id, paiva, aikaviipale from tyovuoro";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $tyovuoro = new Tyovuoro($tulos->hlo_id, $tulos->paiva, $tulos->aikaviipale);
            //$array[] = $muuttuja; lisää muuttujan arrayn perään.
            //Se vastaa melko suoraan ArrayList:in add-metodia.
            $tulokset[] = $tyovuoro;
        }
        return $tulokset;
    }

    public static function haeTyovuorotPalvelunMukaan($palvelu_id) {
        $sql = "SELECT tyovuoro.hlo_id, paiva, aikaviipale from tyovuoro, hlokpalvelut where tyovuoro.hlo_id = hlokpalvelut.hlo_id and palvelu_id = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($palvelu_id));

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $tyovuoro = new Tyovuoro($tulos->hlo_id, $tulos->paiva, $tulos->aikaviipale);
            //$array[] = $muuttuja; lisää muuttujan arrayn perään.
            //Se vastaa melko suoraan ArrayList:in add-metodia.
            $tulokset[] = $tyovuoro;
        }
        return $tulokset;
    }
    
        public static function haeTyovuorotPalvelunJaHenkilonMukaan($palvelu_id, $hlo_id) {
        $sql = "SELECT tyovuoro.hlo_id, paiva, aikaviipale from tyovuoro, hlokpalvelut where tyovuoro.hlo_id = hlokpalvelut.hlo_id and palvelu_id = ? and hlokpalvelut.hlo_id = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($palvelu_id, $hlo_id));

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $tyovuoro = new Tyovuoro($tulos->hlo_id, $tulos->paiva, $tulos->aikaviipale);
            //$array[] = $muuttuja; lisää muuttujan arrayn perään.
            //Se vastaa melko suoraan ArrayList:in add-metodia.
            $tulokset[] = $tyovuoro;
        }
        return $tulokset;
    }

    public static function asetaHenkilonTyovuorot($tyovuorot, $tyontekija_id) {
        Tyovuoro::poistaKaikkiHenkilonTyovuorot($tyontekija_id);

        foreach ($tyovuorot as $tyovuoro) {
            $sql = "insert into tyovuoro (hlo_id, paiva, aikaviipale) values (?, ?, ?)";
            $kysely = getTietokantayhteys()->prepare($sql);
            $kysely->execute(array($tyovuoro->getTyontekija_id(), $tyovuoro->getViikonpv(), $tyovuoro->getAikaviipale()));
            
        }
    }

    public static function poistaKaikkiHenkilonTyovuorot($tyontekija_id) {
        $sql = "DELETE from tyovuoro where hlo_id = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($tyontekija_id));
    }

}