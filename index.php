<?php
require 'kirjastot/palvelu.php';
require 'kirjastot/sql.php';

$sql = "SELECT palvelu_id, kesto, kuvaus, hinta from palvelu";
$kysely = getTietokantayhteys()->prepare($sql);
$kysely->execute();

$assosiaatiotaulu = $kysely->fetch();


?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <p>
            <?php echo "moi"; ?>
            <?php echo $assosiaatiotaulu['kesto']; ?>

        </p>

        <p>Moikkelis moi! Tänään muun muassa</p>
        <ul>
            <li>Tehdään lista</li>
            <li>Testaillaan juttuja</li>
            <li>Syödään sipsejä</li>
        </ul>
        <?php
        // put your code here
        ?>
    </body>
</html>