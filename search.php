<?php

include("PDO.php");

if (isset($_GET['search'])) {

    $CURDATE = date("Y-m-d H:i:s");
    $searchInput = '%' . $_GET['searchInput'] . '%';
    $DATA = $DB->prepare("SELECT numVersion,dateEvenement,version.idEvenement,titre,image,categorie FROM version inner join evenement on version.idEvenement = evenement.idEvenement WHERE titre LIKE :searchInput and dateEvenement >= :CURDATE order by dateEvenement");
    $DATA->bindParam(':searchInput', $searchInput);
    $DATA->bindParam(':CURDATE', $CURDATE, PDO::PARAM_STR);
    $DATA->execute();
}
