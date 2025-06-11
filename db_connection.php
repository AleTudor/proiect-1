<?php
$servername = "localhost";
$username = "root"; // sau alt username dacă ai altul setat
$password = ""; // parola ta de MySQL
$dbname = "anticariat";

// Crează conexiunea
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifică conexiunea
if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}
?>

