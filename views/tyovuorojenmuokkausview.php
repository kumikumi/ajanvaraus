<div id="page_title">
    <h1>Työvuorojen muokkaus</h1>
    <p class="italic"><?php echo $data->tyontekija->getKokonimi() ?></p>
</div>

<form action="tyovuorojenmuokkaus.php?id=<?php echo $data->tyontekija->getId() ?>" method="POST">
    
    <input type="submit" value="Tallenna">
    <input type="hidden" name="id" value="<?php echo $data->tyontekija->getId() ?>">
    
<table class="calendar">
    <tr>
        <td>klo</td>
        <?php foreach (array('MA', 'TI', 'KE', 'TO', 'PE', 'LA', 'SU') as $viikonpv): ?>
            <td><?php echo $viikonpv ?></td>
        <?php endforeach; ?>
    </tr>

    <?php for ($i = 0; $i < 24; $i++): ?>

        <tr>
            <td>
                <?php if ($i % 2 == 0): echo ($i / 2) + 8, ":00-"; endif;?>
            </td>

    <?php foreach (array('MA', 'TI', 'KE', 'TO', 'PE', 'LA', 'SU') as $viikonpv): ?>
                <?php if (isset($data->taulukko[$viikonpv][$i])): ?>
                    <td class="unconfirmed"><input type="checkbox" name="t[]" value="<?php echo $viikonpv; ?>_<?php echo $i; ?>" checked></td>
                <?php else: ?>
                    <td class="unavailable"><input type="checkbox" name="t[]" value="<?php echo $viikonpv; ?>_<?php echo $i; ?>"></td>
                <?php endif; ?>
    <?php endforeach; ?>

        </tr>

<?php endfor; ?>
</table>
</form>