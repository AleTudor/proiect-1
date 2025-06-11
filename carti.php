<?php

include 'db_connection.php';

// Conexiune la baza de date
if (!$conn) {
    die("Conexiune eșuată: " . mysqli_connect_error());
}

// Preia genul din URL și validează-l
$gen_id = isset($_GET['gen_id']) ? (int)$_GET['gen_id'] : 0;

// Interogare SQL pentru a obține cărțile din genul specificat
if ($gen_id) {
    $stmt = $conn->prepare("
        SELECT carti.id, carti.titlu, carti.autor, carti.pret, carti.descriere, carti.imagine, carti.evaluare
        FROM carti
        JOIN carte_gen ON carti.id = carte_gen.carte_id
        JOIN genuri ON carte_gen.gen_id = genuri.id
        WHERE genuri.id = ?
        ORDER BY carti.titlu ASC
    ");
    $stmt->bind_param("i", $gen_id);
} else {
    $stmt = $conn->prepare("
        SELECT carti.id, carti.titlu, carti.autor, carti.pret, carti.descriere, carti.imagine, carti.evaluare
        FROM carti
        ORDER BY carti.titlu ASC
    ");
}

// Execută interogarea și verifică rezultatele
$stmt->execute();
$carti_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stil.css">
    <link rel="stylesheet" href="ANTICARIAT/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <title>Cărți</title>
</head>
<body>

<header>
<section id="header">
    <a href="ANTICARIAT/index.html"><img src="ANTICARIAT/poze carti/Magazinul de anticariat.png" class="logo" alt="logo"></a>


<div>
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

<li> 
<div class="wishlist-icon">   
<a href="ANTICARIAT/Wishlist.html">
      <box-icon type="solid" name="heart"></box-icon>
      <span id="wishlist-count">0</span>
  </a> 
  </div> </li>


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



    <h1 class="titlu-pagina">Cărți</h1>
    <div class="carti-container">
        <ul class="lista-carti">
            <?php while ($carte = $carti_result->fetch_assoc()): ?>
                <li class="carte-card">
                    <!-- Verifică dacă câmpul 'imagine' nu este gol și afișează imaginea -->
                    <?php if (!empty($carte['imagine']) && file_exists($carte['imagine'])): ?>
                        <img src="<?= htmlspecialchars($carte['imagine']) ?>" alt="<?= htmlspecialchars($carte['titlu']) ?>">
                    <?php else: ?>
                        <img src="poze/default.jpg" alt="Imagine indisponibilă">
                    <?php endif; ?>

                    <!-- Afișează titlul cărții -->
                    <h2 class="carte-titlu"><?= htmlspecialchars($carte['titlu']) ?></h2>

                    <!-- Afișează autorul cărții -->
                    <p class="carte-autor">de <?= htmlspecialchars($carte['autor']) ?></p>

                    <!-- Afișează prețul cărții -->
                    <p class="carte-pret">Preț: <?= htmlspecialchars(number_format($carte['pret'], 2)) ?> RON</p>

                    <!-- Afișează descrierea cărții -->
                    <p class="carte-descriere"><?= htmlspecialchars($carte['descriere']) ?></p>

                    <!-- Afișează evaluarea cărții (stele) -->
                    <div class="star">
                        <?php
                        $evaluare = isset($carte['evaluare']) ? (int)$carte['evaluare'] : 0;
                        for ($i = 0; $i < 5; $i++) {
                            echo $i < $evaluare ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                        }
                        ?>
                    </div>


                    <div class="product-actions">
                    <button class="cart-btn add-to-cart" 
        data-id="<?= htmlspecialchars($carte['id']) ?>" 
        data-name="<?= htmlspecialchars($carte['titlu']) ?>" 
        data-price="<?= htmlspecialchars($carte['pret']) ?>"
        data-image="<?= htmlspecialchars($carte['imagine']) ?>"
        onclick="addToCart(this)">
    <i class="fas fa-cart-plus"></i> 
    <svg viewBox="0 0 24 24" class="arr-2" xmlns="http://www.w3.org/2000/svg">
        <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path>
    </svg>
    <span class="text add-to-cart">Adaugă în coș</span>
    <span class="circle"></span>
    <svg viewBox="0 0 24 24" class="arr-1" xmlns="http://www.w3.org/2000/svg">
        <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path>
    </svg>
</button>



<!-- Buton pentru wishlist -->
<box-icon type='solid' name='heart' class="wishlist-btn  add-to-wishlist" 
    data-id="<?= htmlspecialchars($carte['id']) ?>" 
    data-name="<?= htmlspecialchars($carte['titlu']) ?>" 
    data-price="<?= htmlspecialchars($carte['pret']) ?>"
    data-image="<?= htmlspecialchars($carte['imagine']) ?>"
    onclick="addToWishlist(this)">
    <i class="fas fa-heart"></i> 
    </box-icon>


</div>



                </li>
            <?php endwhile; ?>
        </ul>
    </div>


<script src="ANTICARIAT/script.js"></script>
</body>
</html>

<?php
// Închide conexiunea la baza de date
$stmt->close();
$conn->close();
?>
