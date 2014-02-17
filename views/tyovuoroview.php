<div id="page_title">
    <h1>Omat työvuorot</h1>
    <p>Viikko <?php echo $data->viikko ?> (<?php echo $data->vuosi ?>)</p>
    <p class="italic">Essi Esimerkki</p>
</div>

<a href='tyovuorot.php?viikko=<?php echo (($data->viikko)-1) ?>&vuosi=<?php echo ($data->vuosi) ?>'><-- Edellinen viikko</a>
<a href='tyovuorot.php'>Nykyinen viikko</a>
<a href='tyovuorot.php?viikko=<?php echo (($data->viikko)+1) ?>&vuosi=<?php echo ($data->vuosi) ?>'>Seuraava viikko --></a>


<table class="calendar">
    <tr>
        <td>klo</td>
        <?php foreach (array('MA', 'TI', 'KE', 'TO', 'PE', 'LA', 'SU') as $viikonpv): ?>
            <td><?php echo $viikonpv, " ", $data->viikonpaivat[$viikonpv] ?></td>
        <?php endforeach; ?>
    </tr>

    <?php for ($i = 0; $i < 24; $i++): ?>

        <tr>
            <td>
                <?php if ($i % 2 == 0): echo ($i / 2) + 8, ":00-"; endif;?>
            </td>

    <?php foreach (array('MA', 'TI', 'KE', 'TO', 'PE', 'LA', 'SU') as $viikonpv): ?>
                <?php if (isset($data->taulukko[$viikonpv][$i])): ?>
                    <td class="unconfirmed"></td>
                <?php else: ?>
                    <td class="unavailable"></td>
                <?php endif; ?>
    <?php endforeach; ?>

        </tr>

<?php endfor; ?>
</table>
