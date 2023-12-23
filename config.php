<?php
// Configuration de la base de données
$db_host = "127.0.0.1";
$db_user = "root";
$db_password = "";
$db_name = "ecom1_project";

// Connexion à la base de données
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Vérification de la connexion
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
