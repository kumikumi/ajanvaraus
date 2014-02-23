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
            <li><input type="checkbox" name="johtaja" value="true" <?php if ($data->kayttaja->onJohtaja()): ?>checked<?php endif; ?>>Johtaja</li>
        </ul>

        <?php if ($data->kayttaja->onAsiakas()): ?>
            <p>Asiakassaldo: <?php echo $data->kayttaja->getSaldo() ?></p>
        <?php endif; ?>

        <?php if ($data->kayttaja->kuuluuHenkilokuntaan()): ?>
            <a href="tyovuorojenmuokkaus.php?id=<?php echo $data->kayttaja->getId(); ?>">Muokkaa työvuoroja</a><br>
            <p>Palvelut: </p>
            <?php foreach ($data->palvelut as $palvelu): ?>
            <input type="checkbox" name="t[]" value="<?php echo $palvelu->getId(); ?>" <?php if (in_array($palvelu->getId(), $data->tyontekijanPalvelut)): ?>checked<?php endif; ?>><?php echo $palvelu->getNimi(); ?><br>
            <?php endforeach; ?>
        <?php endif; ?>

        
        <button type="submit">Tallenna muutokset</button>
    </form>
</div>
