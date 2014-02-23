<?php

class Varaus {

    private $pvm;
    private $aikaviipale;
    private $palvelu;
    private $toimihlo;
    private $asiakas;
    private $asiakasnimi;
    private $varausnro;

    public function __construct($pvm, $aikaviipale, $palvelu, $toimihlo, $asiakas, $asiakasnimi, $varausnro) {
        $this->pvm = $pvm;
        $this->aikaviipale = $aikaviipale;
        $this->palvelu = $palvelu;
        $this->toimihlo = $toimihlo;
        $this->asiakas = $asiakas;
        $this->asiakasnimi = $asiakasnimi;
        $this->varausnro = $varausnro;
    }

    public function getAsiakasnimi() {
        return $this->asiakasnimi;
    }

    public function setAsiakasnimi($asiakasnimi) {
        $this->asiakasnimi = $asiakasnimi;
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

    public function getKesto() {
        $sql = "select kesto from palvelu where palvelu_id = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($this->palvelu));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            return $tulos->kesto;
        }
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
        require_once 'libs/common.php';

        $alku = date('Y-m-d', timeStamp($vuosi, $viikkonro));
        $loppu = date('Y-m-d', timeStamp($vuosi, nextWeekNumber($viikkonro, $vuosi)));

        $sql = "SELECT pvm, aikaviipale, palvelu, toimihlo, asiakas, asiakasnimi, varausnumero from varaus where pvm >= ? and pvm < ?";

        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($alku, $loppu));

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $varaus = new Varaus($tulos->pvm, $tulos->aikaviipale, $tulos->palvelu, $tulos->toimihlo, $tulos->asiakas, $tulos->asiakasnimi, $tulos->varausnumero);
            //$array[] = $muuttuja; lisää muuttujan arrayn perään.
            //Se vastaa melko suoraan ArrayList:in add-metodia.
            $tulokset[] = $varaus;
        }
        return $tulokset;
    }

    public static function haeTyontekijanVarauksetViikolle($vuosi, $viikkonro, $tyontekija_id) {

        $alku = date('Y-m-d', timeStamp($vuosi, $viikkonro));
        $loppu = date('Y-m-d', timeStamp($vuosi, nextWeekNumber($viikkonro, $vuosi)));

        $sql = "SELECT pvm, aikaviipale, palvelu, toimihlo, asiakas, asiakasnimi, varausnumero from varaus where pvm >= ? and pvm < ? and toimihlo = ?";

        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($alku, $loppu, $tyontekija_id));

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $varaus = new Varaus($tulos->pvm, $tulos->aikaviipale, $tulos->palvelu, $tulos->toimihlo, $tulos->asiakas, $tulos->asiakasnimi, $tulos->varausnumero);
            //$array[] = $muuttuja; lisää muuttujan arrayn perään.
            //Se vastaa melko suoraan ArrayList:in add-metodia.
            $tulokset[] = $varaus;
        }
        return $tulokset;
    }

    public static function etsiAsiakkaanVaraukset($asiakasnro) {

        $sql = "SELECT pvm, aikaviipale, palvelu, toimihlo, asiakas, asiakasnimi, varausnumero from varaus where asiakas = ?";

        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($asiakasnro));

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $varaus = new Varaus($tulos->pvm, $tulos->aikaviipale, $tulos->palvelu, $tulos->toimihlo, $tulos->asiakas, $tulos->asiakasnimi, $tulos->varausnumero);
            $tulokset[] = $varaus;
        }
        return $tulokset;
    }

    public static function etsiAsiakkaanVaraus($pvm, $aika, $asiakas_id) {
        $sql = "select pvm, aikaviipale, toimihlo, palvelu, asiakas, asiakasnimi, varausnumero from varaus where pvm = ? and aikaviipale = ? and asiakas = ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($pvm, $aika, $asiakas_id));


        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            return new Varaus($tulos->pvm, $tulos->aikaviipale, $tulos->palvelu, $tulos->toimihlo, $tulos->asiakas, $tulos->asiakasnimi, $tulos->varausnumero);
        }
    }

    public static function etsiTyontekijanVaraus($pvm, $aika, $hlo_id) {
        $sql = "select pvm, aikaviipale, toimihlo, palvelu, asiakas, asiakasnimi, varausnumero from varaus where pvm = ? and aikaviipale = ? and toimihlo = ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($pvm, $aika, $hlo_id));


        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            return new Varaus($tulos->pvm, $tulos->aikaviipale, $tulos->palvelu, $tulos->toimihlo, $tulos->asiakas, $tulos->asiakasnimi, $tulos->varausnumero);
        }
    }

    public static function etsiVarausVarausnumerolla($varausnumero) {
        $sql = "select pvm, aikaviipale, toimihlo, palvelu, asiakas, asiakasnimi, varausnumero from varaus where varausnumero = ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($varausnumero));


        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            return new Varaus($tulos->pvm, $tulos->aikaviipale, $tulos->palvelu, $tulos->toimihlo, $tulos->asiakas, $tulos->asiakasnimi, $tulos->varausnumero);
        }
    }

    public static function poistaAsiakkaanVaraus($pvm, $aika, $asiakas_id) {
        $sql = "delete from varaus where pvm = ? and aikaviipale = ? and asiakas = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($pvm, $aika, $asiakas_id));
    }

    public static function poistaVarausVarausnumeronPerusteella($varausnumero) {
        $sql = "delete from varaus where varausnumero = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($varausnumero));
    }

    public static function paivitaVarausVarausnumeronPerusteella($varausnumero, $palvelu, $toimihlo, $asiakasnimi) {
        $sql = "UPDATE varaus SET palvelu=?, toimihlo=?, asiakasnimi=? where varausnumero=?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($palvelu, $toimihlo, $asiakasnimi, $varausnumero));
    }

    public static function paivitaRekisteroityneenAsiakkaanVaraus($pvm, $aikaviipale, $asiakas, $palvelu, $toimihlo) {
        $sql = "UPDATE varaus SET palvelu=?, toimihlo=? where asiakas=? and pvm=? and aikaviipale=?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($palvelu, $toimihlo, $asiakas, $pvm, $aikaviipale));
    }

    public static function uusiRekisteroityneenAsiakkaanVaraus($pvm, $aikaviipale, $palvelu, $toimihlo, $asiakas) {
        $sql = "insert into varaus (pvm, aikaviipale, palvelu, toimihlo, asiakas) VALUES (?, ?, ?, ?, ?)";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($pvm, $aikaviipale, $palvelu, $toimihlo, $asiakas));
    }

    public static function uusiRekisteroitymattomanAsiakkaanVaraus($pvm, $aikaviipale, $palvelu, $toimihlo, $asiakasnimi, $varausnro) {
        $sql = "insert into varaus (pvm, aikaviipale, palvelu, toimihlo, asiakasnimi, varausnumero) VALUES (?, ?, ?, ?, ?, ?)";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($pvm, $aikaviipale, $palvelu, $toimihlo, $asiakasnimi, $varausnro));
    }

    public static function annaUusiVarausnumero() {
        //joo en tiedä
        $sql = "select count(*), max(varausnumero) from varaus";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();


        $tulos = $kysely->fetchObject();
        return $tulos->count * 53 + $tulos->max + 17;
    }

}

?>
