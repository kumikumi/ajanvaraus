<div id="page_title">
    <h1>Ajanvaraus</h1>
</div>

<div id="page">

    Päivämäärä: <?php echo $data->varaus->getPvm(); ?> <br>
    <?php if (isset($data->asiakas)): ?>
        Asiakas: <a href="kayttaja.php?id=<?php echo $data->asiakas->getId(); ?>"><?php echo $data->asiakas->getKokonimi(); ?></a><br>
    <?php endif; ?>
    <?php if ($data->varaus->getAsiakasnimi()): ?>
        Asiakasnimi: <?php echo $data->varaus->getAsiakasnimi(); ?><br>
    <?php endif; ?>
    Kellonaika: <?php echo aikaviipaleTekstina($data->varaus->getAikaviipale()); ?> <br>
    Palvelu: <?php echo $data->palvelu; ?> <br>
    Työntekijä: <?php echo $data->toimihlo->getKokonimi(); ?>

</div>


