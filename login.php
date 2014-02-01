<?php

require_once 'libs/common.php';
require_once 'libs/tietokantayhteys.php';


  
  if (empty($_POST["username"]) || empty($_POST["password"])) {
     /* Käytetään omassa kirjastotiedostossa määriteltyä näkymännäyttöfunktioita */
    naytaNakyma("loginview.php");
    exit(); // Lopetetaan suoritus tähän. Kutsun voi sijoittaa myös naytaNakyma-funktioon, niin sitä ei tarvitse toistaa joka paikassa
  }

  $kayttajatunnus = $_POST["username"];
  $salasana = $_POST["password"];
  
  $kayttaja = Kayttaja::getKayttajaTunnuksilla($kayttajatunnus, $salasana);
  
  /* Tarkistetaan onko parametrina saatu oikeat tunnukset */
  if (isset($kayttaja)){
    $_SESSION['kayttaja'] = $kayttaja;
    $_SESSION['nimi'] = $kayttaja->getKokonimi();
    
    header('Location: index.php');
  } else {
    /* Väärän tunnuksen syöttänyt saa eteensä kirjautumislomakkeen. */
    naytaNakyma("loginview.php", array(
        "kayttaja" => $kayttajatunnus,
        "virhe" => "Kirjautuminen epäonnistui! Antamasi tunnus tai salasana on väärä."
    ));
    
  }