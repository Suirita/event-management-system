<?php

// Query to count the number of tickets already sold for a specific version
$query = 'SELECT count(codeBillet) FROM billet INNER JOIN facture ON billet.idFacture = facture.idFacture WHERE numVersion = :numVersion';
$DATA = $DB->prepare($query);
$DATA->bindParam(':numVersion', $numVersion, PDO::PARAM_INT);
$DATA->execute();
$billetCount = $DATA->fetch(PDO::FETCH_ASSOC); // Fetching result

// Query to get the capacity of the room for a specific version
$query = 'SELECT capacite from version INNER JOIN salle ON version.numSalle = salle.numSalle WHERE numVersion = :numVersion';
$DATA = $DB->prepare($query);
$DATA->bindParam(':numVersion', $numVersion, PDO::PARAM_INT);
$DATA->execute();
$salleCapacite = $DATA->fetch(PDO::FETCH_ASSOC); // Fetching result

// Calculating the number of tickets left
$ticketLeft = $salleCapacite['capacite'] - $billetCount['count(codeBillet)'];