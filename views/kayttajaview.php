<div id="page_title">
    <h1><?php echo $data->kayttaja->getKokonimi(); ?></h1>
</div>

<div id="page">
    <p>Käyttäjätunnus: <?php echo $data->kayttaja->getKayttajatunnus() ?></p>
    <p>Nimi: <?php echo $data->kayttaja->getKokonimi() ?></p>
    <p>Roolit:</p>
    <ul>
        <?php if ($data->kayttaja->kuuluuHenkilokuntaan()): ?>
            <li>Henkilökunnan jäsen</li>
        <?php endif; ?>
        <?php if ($data->kayttaja->onAsiakas()): ?>
            <li>Asiakas, saldo: <?php echo $data->kayttaja->getSaldo() ?></li>
        <?php endif; ?>
        <?php if ($data->kayttaja->onJohtaja()): ?>
            <li>Johtoryhmän jäsen</li>
        <?php endif; ?>
    </ul>
</div>
