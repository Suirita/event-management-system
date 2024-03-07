<?php

session_start();
include 'PDO.php';

if (isset($_POST['saveEdit'])) {

    $firsName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($password == "******") {
        $sql = "UPDATE utilisateur SET nom= :lastName, prenom= :firstName, email= :email";
    } else {
        $sql .= ", password= :password";
    }
    $sql .= " WHERE idUtilisateur= :id";
    $DATA = $DB->prepare($sql);
    $DATA->bindParam(':firstName', $firsName);
    $DATA->bindParam(':lastName', $lastName);
    $DATA->bindParam(':email', $email);
    if ($password != "******") {
        password_hash($password, PASSWORD_DEFAULT);
        $DATA->bindParam(':password', $password);
    }
    $DATA->bindParam(':id', $_SESSION['id']);
    $DATA->execute();

    header('Location: profile.php');
    exit();
}
