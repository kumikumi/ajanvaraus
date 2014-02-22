<div id="page_title">
    <h1><?php echo $data->otsikko ?></h1>
</div>

<div id="page">

    <p>Päivämäärä: <?php echo $data->varaus->getPvm(); ?></p>

    <form action="ajanvaraus.php" method="POST">
        <input type="hidden" name="varausnumero" value=<?php
        if (isset($_GET["varausnumero"])): echo $_GET["varausnumero"];
        endif;
        ?>>
        <input type="hidden" name="date" value=<?php echo $data->varaus->getPvm(); ?>>
        <input type="hidden" name="time" value=<?php echo $data->varaus->getAikaviipale(); ?>>
        <?php if ($data->rekisteroitynyt): ?>
            Asiakas: <?php echo $data->varaus->getAsiakasnimi(); ?><br>
        <?php else: ?>
            Asiakkaan nimi: <input type="text" name="nimi" value="<?php echo $data->varaus->getAsiakasnimi() ?>"><br>
        <?php endif; ?>
        Kellonaika: <?php echo aikaviipaleTekstina($data->varaus->getAikaviipale()); ?> <br>

        Palvelu: <select name="palvelu" onchange="this.form.method = 'get';
                this.form.submit()">
                             <?php foreach ($data->palvelut as $palvelu): ?>

                <?php if ($data->varaus->getPalvelu() == $palvelu->getId()): ?>
                    <option value="<?php echo $palvelu->getId() ?>" selected><?php echo $palvelu->getNimi(), ' (', $palvelu->getKesto() * 30, 'min)'; ?></option>
                <?php else: ?>
                    <option value="<?php echo $palvelu->getId() ?>"><?php echo $palvelu->getNimi(), ' (', $palvelu->getKesto() * 30, 'min)'; ?></option>
                <?php endif; ?>

            <?php endforeach; ?>
        </select> 



        <?php if (empty($data->tyontekijat)): ?>

            <span style="color: red;">Valittua palvelua ei saatavilla tähän aikaan:(</span>

        <?php else: ?>
            Kampaaja: <select name="staff">
                <?php foreach ($data->tyontekijat as $tyontekija): ?> 
                    <?php if ($data->varaus->getToimihlo() == $tyontekija->getId()): ?>
                        <option value="<?php echo $tyontekija->getId(); ?>" selected><?php echo $tyontekija->getKokonimi(); ?></option>
                    <?php else: ?>
                        <option value="<?php echo $tyontekija->getId(); ?>"><?php echo $tyontekija->getKokonimi(); ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>



            <?php if ($data->exists): ?>
                <input type="submit" value="Tallenna muutokset">
            </form>
        <?php else: ?>
            <input type="submit" value="Varaa aika">
            </form>
        <?php endif; ?>

    <?php endif; ?>


    <?php if ($data->exists): ?>
        </form>
        <form action="poistavaraus.php" method="POST">
            <input type="hidden" name="date" value=<?php echo $data->varaus->getPvm(); ?>>
            <input type="hidden" name="time" value=<?php echo $data->varaus->getAikaviipale(); ?>>
            <input type="hidden" name="varausnumero" value=<?php if (isset($_GET["varausnumero"])): echo $_GET["varausnumero"];
    endif;
        ?>>
            <input type="submit" value="Poista varaus"></form>

    <?php endif; ?>




