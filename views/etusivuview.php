<div id="sidebar">

    <div id="sidebar_box">
        <p>
            Rekisteröidy kanta-asiakkaaksi ja saat paremmat edut
        </p>
        <p><a href="reksivu.html">paina tosta</a></p>
    </div>
    <div id="sidebar_box">
        <p>
            Oletko kanta-asiakas? Kirjoittaudu sisään järjestelmään
        </p>
        <form action="login" method="POST">
            Käyttäjänimi: <input type="text" name="username" />
            Salasana: <input type="password" name="password" />
            <button type="submit">Kirjaudu</button>
        </form>
    </div>

</div>

<h1> Kalenteri </h1>
<p>Viikko <?php echo $data->viikko ?> (<?php echo $data->vuosi ?>)</p>

<ul>
    <?php foreach ($data->varaukset as $varaus): ?>
    <li>Varaus: <?php echo viikonPaivaTekstina(date('N', strtotime($varaus->getPvm())))?></li>
    <?php endforeach; ?>
</ul>

<a href='index.php?viikko=<?php echo (($data->viikko)-1) ?>&vuosi=<?php echo ($data->vuosi) ?>'><-- Edellinen viikko</a>
<a href='index.php'>Nykyinen viikko</a>
<a href='index.php?viikko=<?php echo (($data->viikko)+1) ?>&vuosi=<?php echo ($data->vuosi) ?>'>Seuraava viikko --></a>

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
                    <td class="free"><a href="varaus.html">varaa</a></td>
                <?php else: ?>
                    <td class="unavailable"></td>
                <?php endif; ?>
    <?php endforeach; ?>

        </tr>

<?php endfor; ?>
</table>
