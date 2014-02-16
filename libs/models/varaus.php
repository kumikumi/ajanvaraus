<?php

class Varaus {

    private $pvm;
    private $aikaviipale;
    private $palvelu;
    private $toimihlo;
    private $asiakas;
    private $varausnro;

    public function __construct($pvm, $aikaviipale, $palvelu, $toimihlo, $asiakas) {
        $this->pvm = $pvm;
        $this->aikaviipale = $aikaviipale;
        $this->palvelu = $palvelu;
        $this->toimihlo = $toimihlo;
        $this->asiakas = $asiakas;
    }

    public function getPvm() {
        return $this->pvm;
    }

    public function setPvm($pvm) {
        $this->pvm = $pvm;
    }

    public function getAikaviipale() {
        return $this->aikaviipale;
    }

    public function setAikaviipale($aikaviipale) {
        $this->aikaviipale = $aikaviipale;
    }

    public function getPalvelu() {
        return $this->palvelu;
    }

    public function setPalvelu($palvelu) {
        $this->palvelu = $palvelu;
    }

    public function getToimihlo() {
        return $this->toimihlo;
    }

    public function setToimihlo($toimihlo) {
        $this->toimihlo = $toimihlo;
    }

    public function getAsiakas() {
        return $this->asiakas;
    }

    public function setAsiakas($asiakas) {
        $this->asiakas = $asiakas;
    }

    public function getVarausnro() {
        return $this->varausnro;
    }

    public function setVarausnro($varausnro) {
        $this->varausnro = $varausnro;
    }

    public static function haeVarauksetViikolle($vuosi, $viikkonro) {
        
        $alku = date('Y-m-d', timeStamp($vuosi, $viikkonro));
        $loppu = date('Y-m-d', timeStamp($vuosi, nextWeekNumber($viikkonro)));
        
//        echo $alku;
//        echo $loppu;
        
        $sql = "SELECT pvm, aikaviipale, palvelu, toimihlo, asiakas from varaus where pvm >= ? and pvm < ?";
        
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($alku, $loppu));

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $varaus = new Varaus($tulos->pvm, $tulos->aikaviipale, $tulos->palvelu, $tulos->toimihlo, $tulos->asiakas);
            //$array[] = $muuttuja; lisää muuttujan arrayn perään.
            //Se vastaa melko suoraan ArrayList:in add-metodia.
            $tulokset[] = $varaus;
        }
        return $tulokset;
    }

}

?>
