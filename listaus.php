<?php
require 'kirjastot/palvelu.php';
require 'kirjastot/sql.php';

$sql = "SELECT palvelu_id, kesto, kuvaus, hinta from palvelu";
$kysely = getTietokantayhteys()->prepare($sql); 
$kysely->execute();

$rivit = $kysely->fetchAll();


//Lista asioista array-tietotyyppiin laitettuna:
$lista = array("Kirahvi", "Trumpetti", "Jeesus", "Parta");
?>

<!DOCTYPE HTML>
<html>
  <head><title>Otsikko</title></head>
  <body>
    <h1>Testi</h1>
<?php echo $rivit[0]['tunnus']; ?>
  </body>
</html>
