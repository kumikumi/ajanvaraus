<?php

function viikonpaivienTimestampit($vuosi, $viikko) {
    $palautus = array();

    $timestamp = timeStamp(intval($vuosi), intval($viikko));

    $palautus['MA'] = $timestamp;
    $palautus['TI'] = strtotime('+1 day', $timestamp);
    $palautus['KE'] = strtotime('+2 day', $timestamp);
    $palautus['TO'] = strtotime('+3 day', $timestamp);
    $palautus['PE'] = strtotime('+4 day', $timestamp);
    $palautus['LA'] = strtotime('+5 day', $timestamp);
    $palautus['SU'] = strtotime('+6 day', $timestamp);
    return $palautus;
}

function muodostaTyovuoroKalenteri($kysyttyHenkilo, $vuosi, $viikko) {
    require_once 'libs/models/varaus.php';
    $tyovuorot = Tyovuoro::haeHenkilonTyovuorot($kysyttyHenkilo);
    $varaukset = Varaus::haeTyontekijanVarauksetViikolle($vuosi, $viikko, $kysyttyHenkilo);

    $kalenteri = array("MA" => array(), "TI" => array(), "KE" => array(), "TO" => array(), "PE" => array(), "LA" => array(), "SU" => array());

    foreach ($tyovuorot as $tyovuoro) {
        $kalenteri[$tyovuoro->getViikonpv()][$tyovuoro->getAikaviipale()] = 0;
    }

    foreach ($varaukset as $varaus) {
        $kalenteri[varauksenViikonPv($varaus)][$varaus->getAikaviipale()] = $varaus;
    }

    return $kalenteri;
}

function muodostaKalenteri($kysyttyPalvelu, $kysyttyHenkilo, $vuosi, $viikko) {
    $taulukko = muodostaTaulukko($kysyttyPalvelu, $kysyttyHenkilo, $vuosi, $viikko);

    $kalenteri = array("MA" => array(), "TI" => array(), "KE" => array(), "TO" => array(), "PE" => array(), "LA" => array(), "SU" => array());

    foreach ($taulukko as $viikonpvnimi => $aikaviipaleet) {
        foreach ($aikaviipaleet as $aikaviipale => $tyontekijat) {
            $kalenteri[$viikonpvnimi][$aikaviipale] = "taken";
            foreach ($tyontekijat as $tyontekija) {
                if ($tyontekija == "not_enough_time") {
                    $kalenteri[$viikonpvnimi][$aikaviipale] = "not_enough_time";
                }
                if ($tyontekija == "available") {
                    $kalenteri[$viikonpvnimi][$aikaviipale] = "available";
                }
                if ($tyontekija == "my_own") {
                    $kalenteri[$viikonpvnimi][$aikaviipale] = "my_own";
                    break;
                }
                if ($tyontekija == "my_own_extended") {
                    $kalenteri[$viikonpvnimi][$aikaviipale] = "my_own_extended";
                    break;
                }
            }
        }
    }
    $kalenteri = poistaMenneetAjat($kalenteri, $vuosi, $viikko);

    return $kalenteri;
}

function muodostaTaulukko($kysyttyPalvelu, $kysyttyHenkiloId, $vuosi, $viikko) {
    require_once 'libs/models/varaus.php';
    $taulukko = array("MA" => array(), "TI" => array(), "KE" => array(), "TO" => array(), "PE" => array(), "LA" => array(), "SU" => array());
    if ($kysyttyPalvelu) {
        $tyovuorot = Tyovuoro::haeTyovuorotPalvelunMukaan($kysyttyPalvelu->getId());
    } else {
        $tyovuorot = Tyovuoro::haeTyovuorot();
    }

    foreach ($tyovuorot as $tyovuoro) {
        if (!isset($taulukko[$tyovuoro->getViikonpv()][$tyovuoro->getAikaviipale()])) {
            $taulukko[$tyovuoro->getViikonpv()][$tyovuoro->getAikaviipale()] = array();
        }
        $taulukko[$tyovuoro->getViikonpv()][$tyovuoro->getAikaviipale()][$tyovuoro->getTyontekija_id()] = "available";
    }

    $varaukset = Varaus::haeVarauksetViikolle($vuosi, $viikko);

    foreach ($varaukset as $varaus) {
        if (isset($taulukko[varauksenViikonPv($varaus)][$varaus->getAikaviipale()]) && isset($taulukko[varauksenViikonPv($varaus)][$varaus->getAikaviipale()][$varaus->getToimihlo()])) {
            if (isset($_SESSION['kayttaja']) && $_SESSION['kayttaja']->getId() == $varaus->getAsiakas()) {
                $taulukko[varauksenViikonPv($varaus)][$varaus->getAikaviipale()][$varaus->getToimihlo()] = "my_own";
            } else {
                $taulukko[varauksenViikonPv($varaus)][$varaus->getAikaviipale()][$varaus->getToimihlo()] = "taken";
            }
            $taulukko = jatkaVarausta($taulukko, varauksenViikonPv($varaus), $varaus->getAikaviipale(), $varaus->getToimihlo(), $varaus->getKesto());
        }
    }

// Seuraavaksi karsitaan listasta kaikki sellaiset ajat, joitten jälkeen ei jää tarpeeksi tilaa
    if ($kysyttyPalvelu) {
        $taulukko = poistaLiianLyhyetAjat($taulukko, $kysyttyPalvelu);
    }
    return $taulukko;
}

function vapaitaPerakkain($taulukko, $viikonpv, $aikaviipale, $tyontekija_id) {
    $palautus = 0;
    while (isset($taulukko[$viikonpv][$aikaviipale][$tyontekija_id]) && ($taulukko[$viikonpv][$aikaviipale][$tyontekija_id] == "available" || $taulukko[$viikonpv][$aikaviipale][$tyontekija_id] == "not_enough_time") && $aikaviipale < 24) {
        $palautus++;
        $aikaviipale++;
    }
    //echo "taulukko[" . $viikonpv . "][" . $aikaviipale . "][" . $tyontekija_id . "] = " . $palautus . "<br>";
    return $palautus;
}

function jatkaVarausta($taulukko, $viikonpv, $aikaviipale, $tyontekija_id, $kesto) {
    //echo "jatkaVarausta(". $taulukko .", ". $viikonpv. ", ". $aikaviipale.", ". $tyontekija_id .", ".$kesto.")<br>";
    $tila = $taulukko[$viikonpv][$aikaviipale][$tyontekija_id];
    //echo "tila: ".$tila."<br>";
    if ($tila == "my_own") {
        $kesto--;
        while ($kesto > 0) {
            //echo "kesto: " .$kesto ."<br>";
            $aikaviipale++;
            $kesto--;
            $taulukko[$viikonpv][$aikaviipale][$tyontekija_id] = $tila . "_extended";
        }
    } else {
        $kesto--;
        while ($kesto > 0) {
            //echo "kesto: " .$kesto ."<br>";
            $aikaviipale++;
            $kesto--;
            $taulukko[$viikonpv][$aikaviipale][$tyontekija_id] = $tila;
        }
    }
    return $taulukko;
}

function poistaLiianLyhyetAjat($taulukko, $kysyttyPalvelu) {
    foreach ($taulukko as $viikonpvnimi => $aikaviipaleet) {
        foreach ($aikaviipaleet as $aikaviipale => $tyontekijat) {
            foreach ($tyontekijat as $tyontekijanimi => $tila) {
                if ($taulukko[$viikonpvnimi][$aikaviipale][$tyontekijanimi] == "available") {
                    if (vapaitaPerakkain($taulukko, $viikonpvnimi, $aikaviipale, $tyontekijanimi) < $kysyttyPalvelu->getKesto()) {
                        $taulukko[$viikonpvnimi][$aikaviipale][$tyontekijanimi] = "not_enough_time";
                    }
                }
            }
        }
    }
    return $taulukko;
}

function poistaMenneetAjat($kalenteri, $vuosi, $viikko) {
    $timestampit = viikonpaivienTimestampit($vuosi, $viikko);
    foreach ($kalenteri as $viikonpvnimi => $aikaviipaleet) {
        for ($aikaviipale = 0; $aikaviipale < 24; $aikaviipale++) {
//            echo "timestamp[". $viikonpvnimi ."]=".$timestampit[$viikonpvnimi]."<br>";
//            echo "aikaviipale=".$aikaviipale."<br>";
//            echo "aikaviipale*1800 =". $aikaviipale*1800 ."<br>";
            if ($timestampit[$viikonpvnimi] + $aikaviipale * 1800 + 28800 < time()) {
                $kalenteri[$viikonpvnimi][$aikaviipale] = "black";
            }
        }
    }
    return $kalenteri;
}

?>
