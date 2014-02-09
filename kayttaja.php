<?php
require_once 'libs/common.php';

  if (!isset($_SESSION['kayttaja'])){
    header('Location: index.php');
    exit();
  }
  
  naytaNakyma("kayttajaview.php");
  
?>
