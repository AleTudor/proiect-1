<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formular Contact</title>
    <link rel="stylesheet" href="style.css"> <!-- Legătura către CSS -->
</head>
<body>





<?php
// Setează conexiunea la baza de date
$servername = "localhost";
$username = "root";  // Modifică acest lucru dacă ai un alt utilizator
$password = "";  // Modifică acest lucru dacă ai o parolă
$dbname = "anticariat";  // Numele bazei de date

// Creează conexiunea
$conn = new mysqli($servername, $username, $password, $dbname);




// Verifică dacă conexiunea a fost reușită
if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Preia datele din formular
    $nume = $_POST['nume'];
    $email = $_POST['email'];
    $subiect = $_POST['subiect'];
    $mesaj = $_POST['mesaj'];

    // Protejează datele împotriva injecției SQL
    $nume = $conn->real_escape_string($nume);
    $email = $conn->real_escape_string($email);
    $subiect = $conn->real_escape_string($subiect);
    $mesaj = $conn->real_escape_string($mesaj);

    // Creează interogarea SQL pentru a insera datele
    $sql = "INSERT INTO contact (nume, email, subiect, mesaj) VALUES ('$nume', '$email', '$subiect', '$mesaj')";

   // Execută interogarea
// Execută interogarea
if ($conn->query($sql) === TRUE) {
    echo "
    <div class='mail-mesaj success' id='success-msg'>
        <div class='message-content'>
            <p><span class='icon'>✔️</span> Mesajul a fost trimis cu succes!</p>
            <p><span class='icon'>📧</span> Te vom contacta în cel mai scurt timp.</p>
        </div>
        <a href='contact.html' class='btn-contact2'>Înapoi</a>
    </div>";
} else {
    echo "<div class='mail-mesaj error' id='error-msg'>
            <span class='icon'>❌</span> Eroare la trimiterea mesajului: " . $conn->error . "
          </div>";
}
 
}

// Închide conexiunea la baza de date
$conn->close();
?>



