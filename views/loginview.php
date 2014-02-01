<div id="page">

    <form action="login.php" method="POST">
        <?php if (isset($data->kayttaja)): ?>
            Käyttäjänimi: <input type="text" name="username" value="<?php echo $data->kayttaja; ?>" />
        <?php else: ?>
            Käyttäjänimi: <input type="text" name="username" />
        <?php endif; ?>


        Salasana: <input type="password" name="password" />
        <button type="submit">Kirjaudu</button>
    </form>

</div>