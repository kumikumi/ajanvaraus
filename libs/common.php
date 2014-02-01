<?php
  require_once 'config.php';
  require_once 'tietokantayhteys.php';
  require_once 'libs/models/kayttaja.php';
  session_start();
  
  /* Näyttää näkymätiedoston ja lähettää sille muuttujat */
  function naytaNakyma($sivu, $data = array()) {
    $data = (object)$data;
    require 'views/pohja.php';
    die();
  }