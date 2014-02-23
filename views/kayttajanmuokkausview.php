<div id="page_title">
    <h1><?php echo $data->kayttaja->getKokonimi(); ?></h1>
</div>

<div id="page">
    <form action="kayttaja.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $data->kayttaja->getId(); ?>">
        <p>Käyttäjätunnus: <?php echo $data->kayttaja->getKayttajatunnus() ?></p>
        <p>Nimi: <input type="text" name="nimi" value="<?php echo $data->kayttaja->getKokonimi(); ?>"/></p>
        <p>Roolit:</p>
        <ul>
            <li><input type="checkbox" name="henkilokunta" value="true" <?php if ($data->kayttaja->kuuluuHenkilokuntaan()): ?>checked<?php endif; ?>>Henkilökunnan jäsen</li>
            <li><input type="checkbox" name="asiakas" value="true" <?php if ($data->kayttaja->onAsiakas()): ?>checked<?php endif; ?>>Asiakas</li>
            <?php if ($data->kayttaja->onAsiakas()): ?>
                <li>Asiakassaldo: <?php echo $data->kayttaja->getSaldo() ?></li>
            <?php endif; ?>
            <li><input type="checkbox" name="johtaja" value="true" <?php if ($data->kayttaja->onJohtaja()): ?>checked<?php endif; ?>>Johtaja</li>
        </ul>
        <button type="submit">Tallenna muutokset</button>
    </form>
</div>
