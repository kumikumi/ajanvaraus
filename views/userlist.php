<?php
require_once "../libs/models/kayttaja.php";
require_once "../libs/tietokantayhteys.php";
//Lista asioista array-tietotyyppiin laitettuna:
$lista = Kayttaja::getKayttajat();
?>

<HTML>

    <head>
        <TITLE>Kayttajalistaus</TITLE>
        <link rel="stylesheet" type="text/css" href="/ajanvaraus/assets/css/style.css">
    </head>

    <BODY>

        <H1>Käyttäjät</H1>

        <table border="1">
            <THEAD><tr><th>ID</th><th>tunnus</th><th>Salasana</th><th>Koko Nimi</th></tr></THEAD>
            <tbody>
                <?php foreach ($lista as $kayttajatunnus): ?>
            <TR>
                <TD><?php echo $kayttajatunnus->getId(); ?></TD>
                <TD><?php echo $kayttajatunnus->getKayttajatunnus(); ?></TD>
                <TD><?php echo $kayttajatunnus->getSalasana(); ?></TD>
                <TD><?php echo $kayttajatunnus->getKokonimi(); ?></TD>
            </TR>
            <?php endforeach; ?>
        </tbody>

    </table>

</BODY>

</HTML>