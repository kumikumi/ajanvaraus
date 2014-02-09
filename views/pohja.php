<?php require_once 'libs/models/kayttaja.php'; ?>

<html>
    <head>

        <?php if (isset($data->otsikko)): ?>
            <title><?php echo $data->otsikko; ?></title>
        <?php else: ?>
            <title>Otsikko</title>
        <?php endif; ?>



        <link rel="stylesheet" type="text/css" href="/ajanvaraus/assets/css/style.css">
    </head>

    <body>

        <div id="titlebar">

            <div id="titlebar_image">
                <img src="/ajanvaraus/assets/img/logo.png" height="40">
            </div>

            <div id="page_title">
                <h1>Ajanvaraus</h1>
            </div>

        </div>

        <div id="navbar">
            <a class="kalenteri" href="index.php">Kalenteri</a>
            <a class="rek" href="./palvelut.php">Palvelut</a>
            <?php
            if (isset($_SESSION['kayttaja'])):
                $kayttaja = $_SESSION['kayttaja'];
                ?>

                <?php if ($kayttaja->onAsiakas()): ?>
                    <a class="ajanvaraukset" href="./ajanvaraukset.html">Varaukset</a>
                <?php endif; ?>

                <?php if ($kayttaja->kuuluuHenkilokuntaan()): ?>
                    <a class="tyovuorot" href="./tyovuorot.html">Työvuorot</a>
                <?php endif; ?>
                    
                <?php if ($kayttaja->onJohtaja()): ?>
                <a class="henkilosto" href="./henkilosto.html">Henkilöstön hallinta</a>
                <a class="raportit" href="./raportit.html">Raportit</a>
                <?php endif; ?>
                |

                <a class="asdf" href="kayttaja.php"><?php echo $kayttaja->getKokonimi() ?></a>
                <a class="uloskirjaus" href="logout.php">Uloskirjaus</a>
            <?php else: ?>
                <a href="login.php">Kirjaudu sisään</a>
                <a class="rek" href="./reksivu.html">Rekisteröityminen</a>
            <?php endif; ?>

        </div>

        <?php if (isset($data->virhe)): ?>
            <div id ="virhe">
                <p><?php echo $data->virhe; ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($data->ilmoitus)): ?>
            <p><?php echo $data->ilmoitus; ?></p>
        <?php endif; ?>

        <?php require $sivu; ?>

    </body>

</html>
