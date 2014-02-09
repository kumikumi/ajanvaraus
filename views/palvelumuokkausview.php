<div id="page_title">
    <h1><?php echo $data->otsikko ?></h1>
</div>

<div id="page">

    <form action="muokkaapalvelua.php" method="POST">
        <?php if (isset($data->palvelu)): ?>

            <input type="hidden" name="id" value="<?php echo $data->palvelu->getId(); ?>">
            Palvelun nimi: <input type="text" name="nimi" value="<?php echo $data->palvelu->getNimi(); ?>"/><br>
            Palvelun kuvaus: <br>
            <textarea cols="40" rows="5" name="kuvaus"><?php echo $data->palvelu->getKuvaus(); ?></textarea>
            <br>
            Palvelun kesto: <input type="text" name="kesto" value="<?php echo $data->palvelu->getKesto(); ?>"/><br>
            Palvelun hinta: <input type="text" name="hinta" value="<?php echo $data->palvelu->getHinta(); ?>"/><br>

        <?php else: ?>
            header('Location: palvelut.php');
            exit();
        <?php endif; ?>

        <button type="submit">Tallenna</button>
    </form>


</div>


<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
