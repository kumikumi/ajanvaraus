<?php
require_once 'libs/common.php';

  if (!isset($_SESSION['kayttaja'])){
    header('Location: index.php');
    exit();
  }
  echo date("N"), "<br>";
  echo date("W");
  naytaNakyma("tyovuoroview.php");
  
?>
