<div id="page_title">
    <h1>Omat työvuorot</h1>
    <p class="italic">Essi Esimerkki</p>
</div>

<table class="calendar">
    <tr><td>klo</td><td>MA</td><td>TI</td><td>KE</td><td>TO</td><td>PE</td><td>LA</td><td>SU</td></tr>

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
