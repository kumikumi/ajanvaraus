<div id="page_title">
    <h1>Ajan varaus</h1>
</div>

<div id="page">

    <p>Päivämäärä: 12-34-5678</p>

    <form action="ajanvaraus.php" method="POST">
        <input type="hidden" name="date" value="<?php echo $data->date; ?>">
        Asiakkaan nimi: <input type="text" name="nimi"><br>
        Kellonaika: <select name="time">
            <?php for ($i = 0; $i < 24; $i++): ?>
                <option value="<?php echo $i ?>" <?php if ($i == $data->time): echo "selected";
            endif;
                ?>><?php
                    if ($i % 2 == 0): echo ($i / 2) + 8, ":00";
                    else: echo (($i - 1) / 2) + 8, ":30";
                    endif;
                    ?></option>
            <?php endfor; ?>
        </select> <br>

        Palvelu: <select name="palvelu_id">
            <?php foreach ($data->palvelut as $palvelu): ?>

                <?php if ($data->kysyttyPalveluId && $data->kysyttyPalveluId == $palvelu->getId()): ?>
                    <option value="<?php echo $palvelu->getId() ?>" selected><?php echo $palvelu->getNimi(), ' (', $palvelu->getKesto() * 30, 'min)'; ?></option>
                <?php else: ?>
                    <option value="<?php echo $palvelu->getId() ?>"><?php echo $palvelu->getNimi(), ' (', $palvelu->getKesto() * 30, 'min)'; ?></option>
    <?php endif; ?>

            <?php endforeach; ?>
        </select> 
        Kampaaja: <select name="staff">
<?php foreach ($data->tyontekijat as $tyontekija): ?> 
                <option value="<?php echo $tyontekija->getId(); ?>"><?php echo $tyontekija->getKokonimi(); ?></option>
<?php endforeach; ?>
        </select>
        <input type="submit" value="Varaa aika">
    </form>

