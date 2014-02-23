<?php

require_once 'libs/common.php';
require_once 'libs/models/varaus.php';

if (!isset($_SESSION['kayttaja']) || !$_SESSION['kayttaja']->onAsiakas()) {
    header('Location: index.php');
    exit();
}

$varaukset = Varaus::etsiAsiakkaanVaraukset($_SESSION['kayttaja']->getId());

naytaNakyma("varauslistaview.php", array(
    "otsikko" => "Omat varaukset",
    "varaukset" => $varaukset
));
?>