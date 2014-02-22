<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'libs/common.php';
require_once 'libs/models/varaus.php';

if (isset($_SESSION['kayttaja'])) {
    if ($_SESSION['kayttaja']->onAsiakas()) {
        //echo "olet asiakas";

        if (empty($_POST["date"]) || empty($_POST["time"])) {
            header('Location: exit.php');
            exit();
        }

        Varaus::poistaAsiakkaanVaraus($_POST["date"], $_POST["time"], $_SESSION['kayttaja']->getId());
        header('Location: index.php?notice=varauspoistettu');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
} else if (!empty($_POST["varausnumero"])) {
    Varaus::poistaVarausVarausnumeronPerusteella($_POST["varausnumero"]);
    $_SESSION['notice']="Ajanvaraus on poistettu järjestelmästä.";
}

header('Location: index.php');
exit();
?>
