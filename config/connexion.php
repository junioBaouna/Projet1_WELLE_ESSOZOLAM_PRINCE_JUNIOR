<?php
$server = 'localhost';
$userName = "root";
$pwd = "";
$db = "ecom1_project";

$conn = mysqli_connect($server, $userName, $pwd, $db);
if ($conn) {
    echo "Connected to the $db database successfully";
    global $conn;
} else {
    echo "Error : Not connected to the $db database";
}