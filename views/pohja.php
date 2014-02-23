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
                    <a class="ajanvaraukset" href="./varaukset.php">Varaukset</a>
                <?php endif; ?>

                <?php if ($kayttaja->kuuluuHenkilokuntaan()): ?>
                    <a class="tyovuorot" href="./tyovuorot.php">Työvuorot</a>
                <?php endif; ?>

                <?php if ($kayttaja->kuuluuHenkilokuntaan() || $kayttaja->onJohtaja()): ?>
                    <a href="./kayttajat.php">Käyttäjät</a>
                <?php endif; ?>

                <?php if ($kayttaja->onJohtaja()): ?>
                    <a class="henkilosto" href="./henkilosto.php">Henkilöstön hallinta</a>
                    <a class="raportit" href="./yhteenveto.php">Yhteenveto</a>
                <?php endif; ?>
                |

                <a class="asdf" href="kayttaja.php"><?php echo $kayttaja->getKokonimi() ?></a>
                <a class="uloskirjaus" href="logout.php">Uloskirjaus</a>
            <?php else: ?>
                <a href="login.php">Kirjaudu sisään</a>
                <a class="rek" href="./rekisteroityminen.php">Rekisteröityminen</a>
            <?php endif; ?>

        </div>

        <?php if (isset($_SESSION['virhe']) || isset($data->virhe)): ?>
            <div id ="virhe">
                <p>Virhe:</p>
                <?php if (isset($data->virhe)): ?>
                    <?php if (is_array($data->virhe)): ?>
                        <ul>
                            <?php foreach ($data->virhe as $viesti): ?>
                                <li><?php echo $viesti ?></li>
                            <?php endforeach; ?>
                        </ul>

                    <?php else: ?>
                        <p><?php echo $data->virhe ?></p>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['virhe'])): ?>
                    <?php if (is_array($_SESSION['virhe'])): ?>
                        <ul>
                            <?php foreach ($_SESSION['virhe'] as $viesti): ?>
                                <li><?php echo $viesti ?></li>
                            <?php endforeach; ?>
                        </ul>

                    <?php else: ?>
                        <p><?php echo $_SESSION['virhe'] ?></p>
                    <?php endif; ?>
                <?php endif; ?>

            </div>

            <?php $_SESSION['virhe'] = null; ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['notice']) || isset($data->ilmoitus)): ?>

            <div id ="ilmoitus">
                <?php if (isset($data->ilmoitus)): ?><p><?php echo $data->ilmoitus; ?></p><?php endif; ?>
                <?php if (isset($_SESSION['notice'])): ?><p><?php echo $_SESSION['notice']; ?></p><?php endif; ?>
            </div>
            <?php $_SESSION['notice'] = null; ?>
        <?php endif; ?>

        <?php require $sivu; ?>

    </body>

</html>
