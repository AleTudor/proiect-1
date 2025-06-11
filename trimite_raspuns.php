<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $raspuns = mysqli_real_escape_string($conn, $_POST['raspuns']);

    $query = "UPDATE contact SET raspuns='$raspuns', status='raspuns' WHERE id=$id";
    mysqli_query($conn, $query);

    header("Location: admin_dashboard.php"); // Redirecționează înapoi
    exit();
}
?>
