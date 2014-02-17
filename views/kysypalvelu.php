<form action="index.php" method="GET"style="margin-bottom: 0px;">
    <input type="hidden" name="vuosi" value="<?php echo $data->vuosi; ?>">
    <input type="hidden" name="viikko" value="<?php echo $data->viikko; ?>">
    Palvelu: <select name="palvelu">
        <option value="0">(kaikki palvelut)</option>
        <?php foreach ($data->palvelut as $palvelu): ?>
        
        <?php if ($data->kysyttyPalvelu && $data->kysyttyPalvelu->getId() == $palvelu->getId()): ?>
        <option value="<?php echo $palvelu->getId() ?>" selected><?php echo $palvelu->getNimi(), ' (', $palvelu->getKesto() * 30, 'min)'; ?></option>
        <?php else: ?>
        <option value="<?php echo $palvelu->getId() ?>"><?php echo $palvelu->getNimi(), ' (', $palvelu->getKesto() * 30, 'min)'; ?></option>
        <?php endif; ?>
        
        <?php endforeach; ?>
    </select> 
    <button type="submit">Päivitä</button>
</form>