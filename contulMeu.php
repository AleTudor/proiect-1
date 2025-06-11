
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ANTICARIAT/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    <title>Contul meu</title>
</head>
<body class="cont-body">

<header class="header1">
<section id="header">
    <a href="ANTICARIAT/index.html"><img src="ANTICARIAT/poze carti/Magazinul de anticariat.png" class="logo" alt="logo"></a>
    <div>
    <div class="nav_menu" id="nav-menu">
  <ul id="navbar">
    <li><a href="ANTICARIAT/index.html">Acasă</a></li>
    <li><a href="#" id="produse-link">Produse</a>
        <ul class="submenu" id="submenu">
            <li><a href="#" id="carti-link">Cărți</a>
                <ul class="genuri" id="genuri-submenu">
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=1">Acțiune și aventură</a></li>
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=2">Ficțiune</a></li>
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=3">Roman de dragoste</a></li>
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=4">Poezie</a></li>
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=5">Literatură clasică</a></li>
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=6">Literatură contemporană</a></li>
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=7">Dezvoltare personală</a></li>
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=8">Adolescenți</a></li>
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=9">Economie</a></li>
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=10">Horror</a></li>
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=11">Științe</a></li>
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=12">Thriller</a></li>
                    <li><a href="http://localhost/anticariat/carti.php?gen_id=13">Non-ficțiune/Academic</a></li>
                </ul>
            </li>
            <li><a href="http://localhost/anticariat/accesorii.php?">Accesorii</a></li>
            <li><a href="http://localhost/anticariat/muzica.php?">Muzică</a></li>
        </ul>
    </li>
<li><a href="ANTICARIAT/despre_noi.html">Despre noi</a></li>
<li><a href="ANTICARIAT/contact.html">Contact</a></li>
<li> <a  class="account-link" href="http://localhost/anticariat/contulMeu.php"> Contul meu </a></li>
<li> <a href="ANTICARIAT/Wishlist.html" class="wishlist-icon">
      <box-icon type="solid" name="heart"></box-icon>
      <span id="wishlist-count">0</span>
  </a></li>


<li>
  <a href="ANTICARIAT/shop.html" class="cart-icon">
    <img src="ANTICARIAT/poze carti/shop.png" class="shop" alt="">
    <span id="cart-count"></span> <!-- folosit pentru badge-ul roșu -->
</a>
</li>

</ul>
</div>

</section>
</header>

<div class="main-content">
<?php
session_start();
include("db_connection.php"); // Conexiune la baza de date


if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: http://localhost/anticariat/admin_dashboard.php");
    exit;
}



if (isset($_SESSION["valid"])) {
    echo "<p class='message11'><ion-icon name='person-circle-outline' class='user-icon'></ion-icon> Bun venit " . $_SESSION["NumeUtilizator"] . "</p>";
    echo "<p class='message1'><b>Id-ul utilizatorului</b>: " . $_SESSION["Id"] . "</p>";
    echo "<p class='message1'><b>Nume:</b> " . $_SESSION["Nume"] . "</p>";
    echo "<p class='message1'><b>Prenume:</b> " . $_SESSION["Prenume"] . "</p>";
    echo "<p class='message1'><b>Email utilizator:</b> " . $_SESSION["valid"] . "</p>";
    echo "<p class='message1'><b>Varsta utilizatorului:</b> " . $_SESSION["Varsta"] . "</p>";

    // Definim email-ul utilizatorului înainte de interogare
    $email = $_SESSION["valid"]; 

    //  Verificăm dacă conexiunea există
    if (!$conn) {
        die("Eroare la conectarea la baza de date: " . mysqli_connect_error());
    }

    //  Selectăm comenzile utilizatorului pe baza email-ului
    $sql = "SELECT id, order_date FROM comenzi WHERE Email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Eroare la pregătirea interogării: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    
    if (!$stmt->execute()) {
        die("Eroare la execuția interogării: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h3>Comenzile tale:</h3>";
        echo "<ul class='order-list'>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>Comanda #" . $row["id"] . " - Data: " . $row["order_date"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Nu ai nicio comandă efectuată.</p>";
    }
} else {
    header("Location: login.php");
    exit;
}
?>
<a href="logout.php" class="log-btn11">Iesiti din cont</a>
</div>
<script src="ANTICARIAT/script.js"></script>
</body>
</html>
