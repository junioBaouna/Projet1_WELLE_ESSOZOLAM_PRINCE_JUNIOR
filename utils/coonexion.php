<?php
$server = 'localhost';
$userName = "root";
$pwd = "";
$db = "ecom1_projet";

$conn = mysqli_connect($server, $userName, $pwd, $db);
if ($conn) {
    global $conn;
    //echo "Error : Connected to the $db database";

} else {
    echo "Error : Not connected to the $db database";
}
?>