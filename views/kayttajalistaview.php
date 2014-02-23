

<table>
    <tr>
        <th><a href="kayttajat.php?sort=id">Käyttäjä ID</a></th>
        <th><a href="kayttajat.php?sort=tunnus">Käyttäjätunnus</a></th>
        <th><a href="kayttajat.php?sort=nimi">Koko nimi</a></th>
        <th>Asiakas</th>
        <th>Henkilökunta</th>
        <th>Johtaja</th>
    </tr>
    <?php foreach ($data->kayttajat as $kayttaja): ?>
    
        <tr class ="hoverselection" onclick="window.document.location='kayttaja.php?id=<?php echo $kayttaja->getId(); ?>';">
            <td><?php echo $kayttaja->getId()?></td>
            <td><?php echo $kayttaja->getKayttajatunnus()?></td>
            <td><?php echo $kayttaja->getKokonimi()?></td>
            <td><?php echo $kayttaja->onAsiakas()?></td>
            <td><?php echo $kayttaja->kuuluuHenkilokuntaan()?></td>
            <td><?php echo $kayttaja->onJohtaja()?></td>
            
        </tr>
    
    <?php endforeach; ?>
</table>