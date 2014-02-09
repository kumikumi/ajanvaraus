<?php
require_once 'libs/common.php';
require_once 'libs/models/palvelu.php';

$palvelut = Palvelu::getPalvelut();

naytaNakyma("palvelulistaview.php", array(
        "otsikko" => "Palvelut",
        "palvelut" => $palvelut
    ));

?>