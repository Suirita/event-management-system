<?php

session_start();

if (isset($_POST['signUp'])) {
    $name = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    include 'PDO.php';

    $sql = "SELECT * FROM utilisateur WHERE email = :email";
    $DATA = $DB->prepare($sql);
    $DATA->bindParam(':email', $email);
    $DATA->execute();

    // Check if any rows were returned
    if ($DATA->rowCount() == 0) {

        $sql = "INSERT INTO utilisateur (prenom, nom, email, motPasse) VALUES (:firstName, :lastName, :email, :password)";
        $DATA = $DB->prepare($sql);
        $DATA->bindParam(':firstName', $name);
        $DATA->bindParam(':lastName', $lastName);
        $DATA->bindParam(':email', $email);
        $DATA->bindParam(':password', $password);
        $DATA->execute();

        $_SESSION['id'] = $DB->lastInsertId();
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['message'] = "Welcome " . $lastName . " " . $name;
    } else {
        $_SESSION['isLoggedIn'] = false;
        $_SESSION['message'] = "Email already exists !";
    }

    $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    header("Location: $redirect_url");
    exit();
}
