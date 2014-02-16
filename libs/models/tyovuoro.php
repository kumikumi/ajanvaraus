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


}