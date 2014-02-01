<?php
require_once 'libs/common.php';

  if (!isset($_SESSION['kayttaja'])){
    header('Location: index.php');
    exit();
  }
  
  naytaNakyma("kayttajaview.php");
  
  
  

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
