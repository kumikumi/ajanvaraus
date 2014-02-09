<div id="page_title">
    <h1>Käyttäjän profiili</h1>
</div>

<div id="page">
    <p>Käyttäjätunnus: <?php echo $kayttaja->getKayttajatunnus() ?></p>
    <p>Nimi: <?php echo $kayttaja->getKokonimi() ?></p>
    <p>Roolit:</p>
    <ul>
        <?php if ($kayttaja->kuuluuHenkilokuntaan()): ?>
            <li>Henkilökunnan jäsen</li>
        <?php endif; ?>
        <?php if ($kayttaja->onAsiakas()): ?>
            <li>Asiakas, saldo: <?php echo $kayttaja->getSaldo()?></li>
        <?php endif; ?>
    </ul>
</div>
