<?php

include('PDO.php');

if (isset($_GET['sort'])) {
    $category = $_GET['category'];
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];
    $CURDATE = date('Y-m-d H:i:s');

    // Construct the base query
    $query =
        "SELECT numVersion, dateEvenement, version.idEvenement, titre, image, categorie FROM version 
              INNER JOIN evenement ON version.idEvenement = evenement.idEvenement
              WHERE dateEvenement >= :CURDATE";

    // Add condition for start and end dates
    if (!empty($startDate) && !empty($endDate)) {
        if ($startDate > $endDate) {
            echo "The start date can't be after the end date";
        } elseif ($startDate < $CURDATE || $endDate < $CURDATE) {
            echo "The start and end dates can't be in the past";
        } elseif ($startDate <= $endDate && $startDate >= $CURDATE && $endDate >= $CURDATE) {
            $query .= " AND dateEvenement BETWEEN :startDate AND :endDate";
        }
    } elseif (!empty($startDate) || !empty($endDate)) {
        echo "Please choose both start and end dates";
    }

    // Add condition for category if not 'all'
    if (!empty($category) && $category != 'all') {
        $query .= " AND categorie = :category";
    }

    // Add order by clause
    $query .= " ORDER BY dateEvenement, titre";

    // Prepare and execute the query
    $DATA = $DB->prepare($query);
    $DATA->bindValue(':CURDATE', $CURDATE, PDO::PARAM_STR);
    if (!empty($startDate) && !empty($endDate) && $startDate <= $endDate && $startDate >= $CURDATE && $endDate >= $CURDATE) {
        $DATA->bindValue(':startDate', $startDate, PDO::PARAM_STR);
        $DATA->bindValue(':endDate', $endDate, PDO::PARAM_STR);
    }
    if (!empty($category) && $category != 'all') {
        $DATA->bindValue(':category', $category, PDO::PARAM_STR);
    }
    $DATA->execute();
}
