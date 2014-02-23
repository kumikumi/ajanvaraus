<div id="page_title">
    <h1>Työntekijät</h1>
</div>

<div id="page">

    <ul>
    
    <?php foreach ($data->tyontekijat as $tyontekija): ?>

        <li>
            <?php echo $tyontekija->getKokonimi(); ?> <a href="tyovuorojenmuokkaus.php?id=<?php echo $tyontekija->getId(); ?>">(työvuorot)</a>
        </li>

    <?php endforeach; ?>
        
    </ul>

</div>
