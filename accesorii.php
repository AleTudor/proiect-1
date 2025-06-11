<?php
include 'db_connection.php';

// Conexiune la baza de date
if (!$conn) {
    die("Conexiune eșuată: " . mysqli_connect_error());
}

// Preia genul din URL și validează-l (dacă este cazul)
$gen_id = isset($_GET['gen_id']) ? (int)$_GET['gen_id'] : 0;

// Interogare SQL pentru a obține produsele din baza de date accesorii
if ($gen_id) {
    // Dacă genul este specificat, filtrăm după acest gen
    $stmt = $conn->prepare("
        SELECT accesorii.id, accesorii.nume, accesorii.creator, accesorii.pret, accesorii.descriere, accesorii.imagine, accesorii.evaluare
        FROM accesorii
        -- Adăugăm eventuala asociere cu o tabelă genuri (dacă există)
        WHERE accesorii.gen_id = ? 
        ORDER BY accesorii.nume ASC
    ");
    $stmt->bind_param("i", $gen_id);
} else {
    // Dacă nu se specifică genul, afișăm toate produsele
    $stmt = $conn->prepare("
        SELECT id, nume, creator, pret, descriere, imagine, evaluare
        FROM accesorii
        ORDER BY nume ASC
    ");
}

// Execută interogarea și verifică rezultatele
$stmt->execute();
$accesorii_result = $stmt->get_result();
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
    <title>Accesorii</title>
</head>
<body>


<header>
<section id="header">
    <a href="#"><img src="ANTICARIAT/poze carti/Magazinul de anticariat.png" class="logo" alt="logo"></a>


<div>
  <ul id="navbar">
    <li><a href="ANTICARIAT/index.html">Acasa</a></li>
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




    <h1 class="titlu-pagina">Accesorii</h1>
    <div class="accesorii-container">
        <ul class="lista-accesorii">
            <?php while ($accesoriu = $accesorii_result->fetch_assoc()): ?>
                <li class="accesoriu-card">
                    <!-- Verifică dacă câmpul 'imagine' nu este gol și afișează imaginea -->
                    <?php if (!empty($accesoriu['imagine']) && file_exists($accesoriu['imagine'])): ?>
                        <img src="<?= htmlspecialchars($accesoriu['imagine']) ?>" alt="<?= htmlspecialchars($accesoriu['nume']) ?>">
                    <?php else: ?>
                        <img src="poze/default.jpg" alt="Imagine indisponibilă">
                    <?php endif; ?>

                    <!-- Afișează numele accesoriului -->
                    <h2 class="carte-titlu"><?= htmlspecialchars($accesoriu['nume']) ?></h2>

                    <!-- Afișează creatorul/accessoriul -->
                    <p class="accesoriu-creator">de <?= htmlspecialchars($accesoriu['creator']) ?></p>

                    <!-- Afișează prețul accesoriului -->
                    <p class="accesoriu-pret">Preț: <?= htmlspecialchars(number_format($accesoriu['pret'], 2)) ?> RON</p>

                    <!-- Afișează descrierea accesoriului -->
                    <p class="accesoriu-descriere"><?= htmlspecialchars($accesoriu['descriere']) ?></p>

                    <!-- Afișează evaluarea accesoriului (stele) -->
                    <div class="star">
                        <?php
                        $evaluare = isset($accesoriu['evaluare']) ? (int)$accesoriu['evaluare'] : 0;
                        for ($i = 0; $i < 5; $i++) {
                            echo $i < $evaluare ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                        }
                        ?>
                    </div>

                    <div class="product-actions  add-to-cart">
                   <!-- Butonul de adăugare în coș -->
                <button class="cart-btn" 

            data-id="<?= htmlspecialchars($accesoriu['id']) ?>" 
            data-name="<?= htmlspecialchars($accesoriu['nume']) ?>" 
            data-price="<?= htmlspecialchars($accesoriu['pret']) ?>"
            data-image="<?= htmlspecialchars($accesoriu['imagine']) ?>"
            onclick="addToCart(this)">
            <i class="fas fa-cart-plus"></i> 



            <svg viewBox="0 0 24 24" class="arr-2" xmlns="http://www.w3.org/2000/svg">
            <path
            d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"
            ></path>
            </svg>
            <span class="text  add-to-cart">Adaugă în coș</span>
            <span class="circle"></span>
            <svg viewBox="0 0 24 24" class="arr-1" xmlns="http://www.w3.org/2000/svg">
            <path
            d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"
            ></path>
            </svg>


            </button>


           
<!-- Buton pentru wishlist -->
<box-icon type='solid' name='heart' class="wishlist-btn" 
    data-id="<?= htmlspecialchars($accesoriu['id']) ?>" 
    data-name="<?= htmlspecialchars($accesoriu['nume']) ?>" 
    data-price="<?= htmlspecialchars($accesoriu['pret']) ?>"
    data-image="<?= htmlspecialchars($accesoriu['imagine']) ?>"
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
