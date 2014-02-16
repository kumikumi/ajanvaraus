<div id="page_title">
<h1>Omat työvuorot</h1>
<p class="italic">Essi Esimerkki</p>
</div>

<?php foreach ($data->tyovuorot as $tyovuoro): ?>

    <?php echo $tyovuoro->getTyontekija_id(), ' | ', $tyovuoro->getViikonpv(), ' | ', $tyovuoro->getAikaviipale(); ?>
<?php endforeach; ?>

<table class="calendar">
<tr><td>klo</td><td>MA</td><td>TI</td><td>KE</td><td>TO</td><td>PE</td><td>LA</td><td>SU</td></tr>
<tr><td>8.00-</td><td class="unavailable"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td class="unavailable"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td>9.00-</td><td class="unavailable"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td class="unavailable"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td>10.00-</td><td class="unavailable"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td class="unconfirmed"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td>11.00-</td><td class="unconfirmed"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td class="unconfirmed"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td>12.00-</td><td class="unconfirmed"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td class="confirmed"><a href="">varaus</a></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td>13.00-</td><td class="confirmed"><a href="">varaus</a></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td class="unconfirmed"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td>14.00-</td><td class="confirmed"><a href="">varaus</a></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td>15.00-</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td>16.00-</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td>17.00-</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td>18.00-</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td>19.00-</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
</table>
