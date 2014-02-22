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
        <!--        <form action="login" method="POST">
                    Käyttäjänimi: <input type="text" name="username" />
                    Salasana: <input type="password" name="password" />
                    <button type="submit">Kirjaudu</button>
                </form>-->
    </div>
    <?php if (!isset($_SESSION['kayttaja'])): ?>
    <div id="sidebar_box">
        <p>
            Oletko varannut ajan? Syötä varausnumerosi tehdäksesi muutoksia.
        </p>
                <form action="ajanvaraus.php" method="GET">
                    Varausnumero: <input type="text" name="varausnumero" />
                    <button type="submit">Tarkastele varausta</button>
                </form>
    </div>
    <?php endif; ?>

</div>

<h1> Kalenteri </h1>
<p>Viikko <?php echo $data->viikko ?> (<?php echo $data->vuosi ?>)</p>

<?php require 'views/kysypalvelu.php' ?>

<a href='index.php?viikko=<?php echo (($data->viikko) - 1) ?>&vuosi=<?php echo ($data->vuosi) ?><?php
if ($data->kysyttyPalvelu): echo "&palvelu=" . $data->kysyttyPalvelu->getId();
endif;
?>'><-- Edellinen viikko</a>
<a href='index.php'>Nykyinen viikko</a>
<a href='index.php?viikko=<?php echo (($data->viikko) + 1) ?>&vuosi=<?php echo ($data->vuosi) ?><?php
if ($data->kysyttyPalvelu): echo "&palvelu=" . $data->kysyttyPalvelu->getId();
endif;
?>'>Seuraava viikko --></a>

<table class="calendar">
    <tr>
        <td>klo</td>
        <?php foreach (array('MA', 'TI', 'KE', 'TO', 'PE', 'LA', 'SU') as $viikonpv): ?>
            <td><?php echo $viikonpv, " ", date("j.n", $data->paivamaarat[$viikonpv]); ?></td>
        <?php endforeach; ?>
    </tr>

    <?php for ($i = 0; $i < 24; $i++): ?>

        <tr>
            <td>
                <?php
                if ($i % 2 == 0): echo ($i / 2) + 8, ":00-";
                endif;
                ?>
            </td>

            <?php foreach (array('MA', 'TI', 'KE', 'TO', 'PE', 'LA', 'SU') as $viikonpv): ?>
                <?php if (isset($data->taulukko[$viikonpv][$i])): ?>
                    <?php if ($data->taulukko[$viikonpv][$i] == "available"): ?>
                        <td class="free"><a href="ajanvaraus.php?date=<?php echo date('Y-m-d', $data->paivamaarat[$viikonpv]); ?>&time=<?php echo $i; ?><?php
                            if ($data->kysyttyPalvelu): echo "&palvelu=" . $data->kysyttyPalvelu->getId();
                            endif;
                            ?>">varaa</a></td>
                        <?php elseif ($data->taulukko[$viikonpv][$i] == "taken"): ?>
                        <td class="taken">(varattu)</td>
                    <?php elseif ($data->taulukko[$viikonpv][$i] == "not_enough_time"): ?>
                        <td class="unconfirmed">(ei ehdi)</td>
                    <?php elseif ($data->taulukko[$viikonpv][$i] == "black"): ?>
                        <td class="black"></td>    
                    <?php elseif ($data->taulukko[$viikonpv][$i] == "my_own_extended"): ?>
                        <td class="my_own"></td>    
                    <?php elseif ($data->taulukko[$viikonpv][$i] == "my_own"): ?>
                        <td class="my_own"><a href="ajanvaraus.php?date=<?php echo date('Y-m-d', $data->paivamaarat[$viikonpv]); ?>&time=<?php echo $i; ?><?php
                            if ($data->kysyttyPalvelu): echo "&palvelu=" . $data->kysyttyPalvelu->getId();
                            endif;
                            ?>">varaus</a></td>
                        <?php else: ?>
                        <td class="taken">VIRHE</td>
                    <?php endif; ?>
                <?php else: ?>
                    <td class="unavailable"></td>
                <?php endif; ?>
            <?php endforeach; ?>

        </tr>

    <?php endfor; ?>
</table>
