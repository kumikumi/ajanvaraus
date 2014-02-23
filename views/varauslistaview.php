<div id="page_title">
    <h1>Omat ajanvaraukset</h1>
</div>

<div id="page">

    <?php if (empty($data->varaukset)): ?>
        <p>Ei varauksia</p>
    <?php else: ?>
        <ul>
            <?php foreach ($data->varaukset as $varaus): ?>
                <li><a href="ajanvaraus.php?date=<?php echo $varaus->getPvm(); ?>&time=<?php echo $varaus->getAikaviipale(); ?>"><?php echo $varaus->getPvm(); ?> klo <?php echo aikaviipaleTekstina($varaus->getAikaviipale()); ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>

</body>