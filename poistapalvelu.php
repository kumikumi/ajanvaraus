<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'libs/common.php';
require_once 'libs/models/palvelu.php';

if (!isset($_SESSION['kayttaja']) || !($_SESSION['kayttaja']->kuuluuHenkilokuntaan())) {
    header('Location: index.php');
    exit();
}

if (!empty($_POST["id"])) {
    $id = (int) $_POST['id'];
    Palvelu::poistaPalvelu($id);
    header('Location: palvelut.php');
    exit;
}

header('Location: palvelut.php');
exit();
?>
