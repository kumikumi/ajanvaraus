<?php

require_once 'libs/common.php';
require_once 'libs/models/tyovuoro.php';
require_once 'libs/models/varaus.php';
require_once 'libs/models/palvelu.php';
require_once 'libs/kalenteri/kalenteri_funktiot.php';

$page = "index.php";

require 'libs/maarita_vuosi_ja_viikko.php';

if (isset($_GET['palvelu']) && filter_var($_GET['palvelu'], FILTER_VALIDATE_INT)) {
    $kysyttyPalvelu = Palvelu::etsi(filter_var($_GET['palvelu'], FILTER_VALIDATE_INT));
} else {
    $kysyttyPalvelu = null;
}

$kalenteri = muodostaKalenteri($kysyttyPalvelu, $vuosi, $viikko);
$paivamaarat = viikonpaivienTimestampit($vuosi, $viikko);

naytaNakyma("etusivuview.php", array(
    "otsikko" => "Etusivu",
    "taulukko" => $kalenteri,
    "viikko" => $viikko,
    "vuosi" => $vuosi,
    "paivamaarat" => $paivamaarat,
    "palvelut" => Palvelu::getPalvelut(),
    "kysyttyPalvelu" => $kysyttyPalvelu
));
?>