<?php

session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    include 'PDO.php';

    // Fetch user record based on email
    $sql = "SELECT * FROM utilisateur WHERE email = :email";
    $DATA = $DB->prepare($sql);
    $DATA->bindParam(':email', $email);
    $DATA->execute();
    $utilisateur = $DATA->fetch(PDO::FETCH_ASSOC);

    // Check if email exists and verify password
    if ($utilisateur) {
        // Verify hashed password
        if (password_verify($password, $utilisateur['motPasse'])) {
            // Password is correct
            // Proceed with login
            $_SESSION['id'] = $utilisateur['idUtilisateur'];
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['message'] = "Welcome back " . $utilisateur['nom'] . " " . $utilisateur['prenom'];
        } else {
            // Password is incorrect
            $_SESSION['isLoggedIn'] = false;
            $_SESSION['message'] = "Wrong password";
        }
    } else {
        // Email not found
        $_SESSION['isLoggedIn'] = false;
        $_SESSION['message'] = "No email found";
    }
    $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    header("Location: $redirect_url");
    exit();
}
