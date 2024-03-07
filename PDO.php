<?php

//connect to database
$dsn = 'mysql:host=localhost;dbname=farha';
$user = 'root';
$pass = 'fahd26092004';

try {
    $DB = new PDO($dsn, $user, $pass);
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'failed ' . $e->getMessage();
}
