<?php

require_once 'libs/common.php';
require_once 'libs/models/tyovuoro.php';

if (!isset($_SESSION['kayttaja']) || !($_SESSION['kayttaja']->onJohtaja()) || !isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

if (!empty($_POST['id'])) {
    
    $uudetTyovuorot = array();
    
    foreach ($_POST['t'] as $value) {
        $osat = explode("_", $value);
        $uudetTyovuorot[] = new Tyovuoro($_POST['id'], $osat[0], $osat[1]);
        
    }    
    Tyovuoro::asetaHenkilonTyovuorot($uudetTyovuorot, $_POST['id']);
    header('Location: tyovuorojenmuokkaus.php?id='.$_GET['id']."&notice=success");
    exit();
}

$tyontekija = Kayttaja::etsiTyontekija($_GET['id']);
if (!isset($tyontekija)) {
    header('Location: index.php');
    exit();
}

$ilmoitus = null;
if (isset($_GET['notice'])) {
    if ($_GET['notice'] == "success") {
        $ilmoitus = "Muutokset tallennettu";
    }
}

$tyovuorot = Tyovuoro::haeHenkilonTyovuorot($_GET['id']);
$taulukko = array("MA" => array(), "TI" => array(), "KE" => array(), "TO" => array(), "PE" => array(), "LA" => array(), "SU" => array());

foreach ($tyovuorot as $tyovuoro) {
    $taulukko[$tyovuoro->getViikonpv()][$tyovuoro->getAikaviipale()] = "1";
}
naytaNakyma("tyovuorojenmuokkausview.php", array(
    "otsikko" => "Työvuorojen muokkaus",
    "taulukko" => $taulukko,
    "ilmoitus" => $ilmoitus,
    "tyontekija" => $tyontekija
));
?>
