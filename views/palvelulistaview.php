<div id="page_title">
    <h1>Palvelut</h1>
</div>

<div id="page">

    <?php if (isset($kayttaja) && ($kayttaja->kuuluuHenkilokuntaan()|| $kayttaja->onJohtaja())): ?>
        <a href="muokkaapalvelua.php?id=-1">Lisää uusi palvelu</a>
    <?php endif; ?>

    <?php foreach ($data->palvelut as $palvelu): ?>

        <h2>
            <?php echo $palvelu->getNimi(), ' (', $palvelu->getKesto() * 30, 'min)'; ?>
        </h2>

        <?php if (isset($kayttaja) && ($kayttaja->kuuluuHenkilokuntaan() || $kayttaja->onJohtaja())): ?>
            <form action="poistapalvelu.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $palvelu->getId() ?>">
                <a href="muokkaapalvelua.php?id=<?php echo $palvelu->getId() ?>">muokkaa</a>
                <input type="submit" value="poista">
            </form>
        <?php endif; ?>


        <p class="italic">Hinta: <?php echo $palvelu->getHinta(); ?>€</p>
        <p><?php echo $palvelu->getKuvaus(); ?></p>

    <?php endforeach; ?>

</div>
