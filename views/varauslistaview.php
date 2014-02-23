<div id="page_title">
    <h1>Omat ajanvaraukset</h1>
</div>

<div id="page">

    <ul>
        <?php foreach ($data->varaukset as $varaus): ?>
            <li><a href="ajanvaraus.php?date=<?php echo $varaus->getPvm(); ?>&time=<?php echo $varaus->getAikaviipale(); ?>"><?php echo $varaus->getPvm(); ?> klo <?php echo aikaviipaleTekstina($varaus->getAikaviipale()); ?></a></li>
        <?php endforeach; ?>
    </ul>

</div>

</body>