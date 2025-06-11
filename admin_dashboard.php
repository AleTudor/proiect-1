<?php
session_start();
include 'db_connection.php';

// Verifică dacă utilizatorul este logat și are rolul de admin
if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // dacă nu e logat sau nu e admin, redirecționează la login
    exit();
}

// Obține numărul de utilizatori excluzând administratorul
$user_count_result = mysqli_query($conn, "SELECT COUNT(*) as user_count FROM utilizatori WHERE role != 'admin'");
$user_count_row = mysqli_fetch_assoc($user_count_result);
$user_count = $user_count_row['user_count'];

// Obține numărul de comenzi
$order_count_result = mysqli_query($conn, "SELECT COUNT(*) as order_count FROM comenzi");
$order_count_row = mysqli_fetch_assoc($order_count_result);
$order_count = $order_count_row['order_count'];


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="ANTICARIAT/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

</head>
<body class="body-admin">
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
    <div class="admin-container">
        <header class="header-admin">
   
        <?php

if (isset($_SESSION["valid"])) {
    echo "<p class='user-admin' ><ion-icon name='person-circle-outline' class='user-icon'></ion-icon> Bun venit " . $_SESSION["NumeUtilizator"] . "</p>"; } ?>
            <h1 class="h1-admin">Dashboard Admin</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </header>

        <div class="admin-stats">
            <div class="stat-box">
                <h2>Număr de utilizatori</h2>
                <p><?php echo $user_count; ?></p>
            </div>
            <div class="stat-box">
                <h2>Număr de comenzi</h2>
                <p><?php echo $order_count; ?></p>
            </div>
        </div>

        <div class="admin-actions">
    <h2>Acțiuni Admin</h2>
    <ul>
        <li><button class="admin-buton"><a href="javascript:void(0);" onclick="toggleSection('users-list')">Vezi utilizatori</a></button></li>
        <li><button class="admin-buton"><a href="javascript:void(0);" onclick="toggleSection('orders-list')">Vezi comenzi</a> </buttoncl></li>
        <!-- Adaugă alte linkuri pentru funcționalități admin, după necesitate -->
    </ul>
<!-- Lista de utilizatori -->
<div id="users-list" class="action-list" style="display: none;">
    <h3>Utilizatori</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nume</th>
            <th>Email</th>
            <th>Vârstă</th>
        </tr>
        <?php
        // Cod PHP pentru a obține utilizatorii din baza de date
        $result = mysqli_query($conn, "SELECT * FROM utilizatori");
        while ($user = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $user['Id'] . "</td>";
            echo "<td>" . $user['Nume'] . " " . $user['Prenume'] . "</td>";
            echo "<td>" . $user['Email'] . "</td>";
            echo "<td>" . $user['Varsta'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <button onclick="toggleSection('users-list')">Ascunde</button>
</div>

<!-- Lista de comenzi -->
<div id="orders-list" class="action-list" style="display: none;">
    <h3>Comenzi</h3>
    <table>
        <tr>
            <th>ID Comandă</th>
            <th>Prenume utilizator</th>
            <th>Nume utilizator</th>
            <th>Adresa utilizator</th>
            <th>Nr. telefon utilizator</th>
            <th>Livrare</th>
            <th>Plata</th>
            <th>Numele de pe card</th>
            <th>Numarul cardului</th>
            <th>Ziua de expirare a cardului</th>
            <th>Luna de expirare a cardului</th>
            <th>CVV</th>
            <th>Data comenzii</th>
            <th>Emailul utilizatorului</th>
        </tr>
        <?php
        // Cod PHP pentru a obține comenzile din baza de date
        $orderResult = mysqli_query($conn, "SELECT * FROM comenzi");
        while ($order = mysqli_fetch_assoc($orderResult)) {
            echo "<tr>";
            echo "<td>" . $order['id'] . "</td>";
            echo "<td>" . $order['prenume'] . "</td>";
            echo "<td>" . $order['nume'] . "</td>";
            echo "<td>" . $order['adresa'] . "</td>";
            echo "<td>" . $order['telefon'] . "</td>";
            echo "<td>" . $order['delivery_option'] . "</td>";
            echo "<td>" . $order['payment'] . "</td>";
            echo "<td>" . $order['cardholder_name'] . "</td>";
            echo "<td>" . $order['card_number'] . "</td>";
            echo "<td>" . $order['expire_day'] . "</td>";
            echo "<td>" . $order['expire_month'] . "</td>";
            echo "<td>" . $order['cvv'] . "</td>";
            echo "<td>" . $order['order_date'] . "</td>";
            echo "<td>" . $order['Email'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <button onclick="toggleSection('orders-list')">Ascunde</button>
</div>



<!-- Lista de mesaje contact -->
<table>
<tr>
        <th colspan="7">Mesaje Contact</th>
    </tr>
    <th>Subiect</th>
        <th>Email</th>
        <th>Nume</th>
        <th>Mesaj</th>
        <th>Data</th>
        <th>Răspuns</th>
        <th>Acțiune</th>
    </tr>
    <?php 
    
    
$query = "SELECT id, nume, email, subiect, mesaj, data_creare, raspuns, status FROM contact ORDER BY data_creare DESC";
$result = mysqli_query($conn, $query);

    
    while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr class="<?php echo ($row['status'] == 'raspuns') ? 'mesaj-raspuns' : 'mesaj-necitit'; ?>">
            <td><?php echo $row['subiect']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['nume']; ?></td>
            <td><?php echo $row['mesaj']; ?></td>
            <td><?php echo $row['data_creare']; ?></td>
            <td><?php echo $row['raspuns'] ? $row['raspuns'] : '<i>Fără răspuns</i>'; ?></td>
            <td>
                <?php if (!$row['raspuns']) { ?>
                    <form method="POST" action="trimite_raspuns.php">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <textarea name="raspuns" required></textarea>
                        <button type="submit">Trimite</button>
                    </form>
                <?php } else { ?>
                    <span style="color: green;">Răspuns trimis</span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>




<script>
    // Funcția pentru a arăta sau ascunde secțiunea
    function toggleSection(sectionId) {
        var section = document.getElementById(sectionId);
        if (section.style.display === "none") {
            section.style.display = "block";
        } else {
            section.style.display = "none";
        }
    }
</script>

    </div>
</body>
</html>
