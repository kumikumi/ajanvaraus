<?php

require_once 'libs/common.php';
require_once 'libs/models/tyovuoro.php';

if (!isset($_SESSION['kayttaja']) || !($_SESSION['kayttaja']->onJohtaja() || $_SESSION['kayttaja']->kuuluuHenkilokuntaan())) {
    header('Location: index.php');
    exit();
}



?>
