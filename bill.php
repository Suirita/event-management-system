<?php
session_start();
include 'PDO.php'; // Including PDO connection

if (isset($_POST['bill'])) { // Checking if form is submitted

    // Checking if user is logged in
    if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] == false) {
        $_SESSION['message'] = "You need to login first to buy a ticket"; // Setting session message
    } else {
        // Getting form data
        $tarifNormal = (int)$_POST['normal']; // Converting to integer for safety
        $tarifReduite = (int)$_POST['reduced']; // Converting to integer for safety
        $numVersion = (int)$_POST['numVersion']; // Converting to integer for safety

        if ($tarifReduite == 0 && $tarifNormal == 0) {
            $_SESSION['message'] = "Please select at least one ticket"; // Setting session message
        } else {

            // Calculating total cost of tickets
            $tarifTotal = $tarifNormal + $tarifReduite;
            // Including ticketLeft.php
            include 'ticketLeft.php';
            // Proceeding if there are enough tickets left
            if ($ticketLeft >= $tarifTotal) {
                // Generating invoice ID, getting current date, and user ID
                $idFacture = substr(uniqid(), 0, 8);
                $dateFacture = date('Y-m-d H:i:s');
                $idUtilisateur = $_SESSION['id'];

                // Inserting invoice details into database
                $query = 'INSERT INTO facture (idFacture, dateFacture, idUtilisateur, numVersion) VALUES (:idFacture, :dateFacture, :idUtilisateur, :numVersion)';
                $DATA = $DB->prepare($query);
                $DATA->bindParam(':idFacture', $idFacture);
                $DATA->bindParam(':dateFacture', $dateFacture);
                $DATA->bindParam(':idUtilisateur', $idUtilisateur);
                $DATA->bindParam(':numVersion', $numVersion, PDO::PARAM_INT);
                $DATA->execute();

                // Inserting normal tickets into database
                for ($i = 0; $i < $tarifNormal; $i++) {
                    include 'ticketLeft.php'; // Including ticketLeft.php

                    // Ensure $ticketLeft and $billetCount are integers
                    $ticketLeft = (int)$ticketLeft;
                    $billetCount = (int)$billetCount;

                    $codeBillet = substr(uniqid(), 7, 5); // Generate unique code for each ticket
                    $typeBillet = "normal";
                    $numplace = $ticketLeft - $billetCount +1;   

                    $query = 'INSERT INTO billet (codeBillet, typeBillet, numplace, idFacture) VALUES (:codeBillet, :typeBillet, :numplace, :idFacture)';
                    $DATA = $DB->prepare($query);
                    $DATA->bindParam(':codeBillet', $codeBillet);
                    $DATA->bindParam(':typeBillet', $typeBillet);
                    $DATA->bindParam(':numplace', $numplace);
                    $DATA->bindParam(':idFacture', $idFacture);
                    $DATA->execute();
                }

                // Inserting reduced tickets into database
                for ($i = 0; $i < $tarifReduite; $i++) {
                    include 'ticketLeft.php'; // Including ticketLeft.php

                    // Ensure $ticketLeft and $billetCount are integers
                    $ticketLeft = (int)$ticketLeft;
                    $billetCount = (int)$billetCount;

                    $codeBillet = substr(uniqid(), 7, 5); // Generate unique code for each ticket
                    $typeBillet = "réduit";
                    $numplace = $ticketLeft - $billetCount +1;

                    $query = 'INSERT INTO billet (codeBillet, typeBillet, numplace, idFacture) VALUES (:codeBillet, :typeBillet, :numplace, :idFacture)';
                    $DATA = $DB->prepare($query);
                    $DATA->bindParam(':codeBillet', $codeBillet);
                    $DATA->bindParam(':typeBillet', $typeBillet);
                    $DATA->bindParam(':numplace', $numplace);
                    $DATA->bindParam(':idFacture', $idFacture);
                    $DATA->execute();
                }

                $_SESSION['message'] = "Bill generated successfully"; // Setting session message

            } elseif ($ticketLeft < $tarifTotal) {
                $_SESSION['ticketLeft'] = $ticketLeft; // Storing remaining tickets in session
                $_SESSION['message'] = "Not enough tickets left"; // Setting session message
            }
        }
    }
    $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    header("Location: $redirect_url");
    exit();
    echo $_SESSION['message'];
}
