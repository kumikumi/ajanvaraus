<div id="page_title">
    <h1>Kanta-asiakkaaksi rekisteröityminen</h1>
</div>

<div id="page">

    <form action ="rekisteroityminen.php" method="POST">
        <input type="hidden" name="submit" value="1">

        <?php if (isset($data->kayttaja)): ?>
            Asiakkaan nimi: <input type="text" name="nimi" value="<?php echo $data->kayttaja->getKokonimi() ?>"><br>
            käyttäjätunnus: <input type="text" name="tunnus"><?php echo $data->kayttaja->getKayttajatunnus() ?><br>
            salasana: <input type="password" name="password"><br>
            salasana uudelleen: <input type="password" name="password2">
        <?php else: ?>
            Asiakkaan nimi: <input type="text" name="nimi"><br>
            käyttäjätunnus: <input type="text" name="tunnus"><br>
            salasana: <input type="password" name="password"><br>
            salasana uudelleen: <input type="password" name="password2">
        <?php endif; ?>


        <button type="submit">Luo tunnus</button>


    </form>

</div>