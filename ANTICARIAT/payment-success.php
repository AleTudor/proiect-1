<?php
// Configurare conexiune la baza de date
$host = 'localhost';
$db   = 'anticariat';
$user = 'root';   // Înlocuiește cu utilizatorul bazei tale de date
$pass = '';       // Înlocuiește cu parola bazei de date
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    exit("Eroare la conectare: " . $e->getMessage());
}

// Verifică dacă formularul a fost trimis corect
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Preluarea datelor din formular, asigurând compatibilitatea cu shop.html
    $prenume        = $_POST['prenume'] ?? '';
    $nume           = $_POST['nume'] ?? '';
    $adresa         = $_POST['adresa'] ?? '';
    $telefon        = $_POST['telefon'] ?? '';
    $deliveryOption = $_POST['delivery_option'] ?? '';
    $cardholderName = $_POST['cardholder_name'] ?? '';
    $cardNumber     = $_POST['card_number'] ?? '';
    $expireDay      = $_POST['day'] ?? '';
    $expireMonth    = $_POST['month'] ?? '';
    $paymentMethod  = $_POST['payment_method'] ?? '';
    $Email          = $_POST['Email'] ?? '';  



    // Validare câmpuri obligatorii
    if (empty($prenume) || empty($nume) || empty($adresa) || empty($telefon)) {
        exit("Toate câmpurile obligatorii trebuie completate!");
    }

    // Validare număr de telefon (doar cifre, min 10 caractere)
    if (!preg_match('/^\d{10,15}$/', $telefon)) {
        exit("Numărul de telefon nu este valid!");
    }

    // Validare card (dacă a fost introdus)
    if (!empty($cardNumber)) {
        if (!preg_match('/^\d{13,19}$/', $cardNumber)) {
            exit("Numărul cardului nu este valid!");
        }
        // Mascare număr card (păstrează doar ultimele 4 cifre)
        $cardNumberMasked = str_repeat('*', strlen($cardNumber) - 4) . substr($cardNumber, -4);
    } else {
        $cardNumberMasked = "N/A";
    }

    // Validare dată expirare
    if (!empty($expireDay) && !empty($expireMonth)) {
        if (!filter_var($expireDay, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 31]]) ||
            !filter_var($expireMonth, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 12]])) {
            exit("Data expirării cardului nu este validă!");
        }
    }

    // Inserare în baza de date
    $sql = "INSERT INTO comenzi (prenume, nume, adresa, telefon, delivery_option, payment, cardholder_name, card_number, expire_day, Email, expire_month, order_date) 
            VALUES (:prenume, :nume, :adresa, :telefon, :delivery_option, :payment, :cardholder_name, :card_number, :expire_day, :Email, :expire_month, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':prenume' => $prenume,
        ':nume' => $nume,
        ':adresa' => $adresa,
        ':telefon' => $telefon,
        ':delivery_option' => $deliveryOption,
        ':payment' => $paymentMethod,
        ':cardholder_name' => $cardholderName,
        ':card_number' => $cardNumberMasked,  // Salvăm doar versiunea mascată
        ':expire_day' => $expireDay,
       ':Email' => $Email ?: null,
        ':expire_month' => $expireMonth
       
        
    ]);

    // Obținerea ultimului ID inserat
    $orderId = $pdo->lastInsertId();

    // Obținerea detaliilor comenzii
    $sql = "SELECT * FROM comenzi WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $orderId]);
    $order = $stmt->fetch();

    if (!$order) {
        exit("Comanda nu a fost găsită.");
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Plată reușită - Factură</title>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body class="success-body">
    <div class="invoice-container">
        <div class="invoice-header">
            <ion-icon name="checkmark-circle"></ion-icon>
            <h1>Plată reușită!</h1>
        </div>
        <div class="invoice-details">
            <h2>Detalii comandă #<?= htmlspecialchars($order['id'] ?? '') ?></h2>
            <p>
                <strong>Nume:</strong> <?= htmlspecialchars(($order['prenume'] ?? '') . ' ' . ($order['nume'] ?? '')) ?><br>
                <strong>Adresă:</strong> <?= htmlspecialchars($order['adresa'] ?? '') ?><br>
                <strong>Telefon:</strong> <?= htmlspecialchars($order['telefon'] ?? '') ?><br>
                <strong>Email:</strong> <?= htmlspecialchars($order['Email'] ?? '') ?><br>
                <strong>Livrare:</strong> <?= htmlspecialchars($order['delivery_option'] ?? '') ?><br>
                <strong>Metoda de plată:</strong> <?= htmlspecialchars($paymentMethod) ?>
            </p>
            <h3>Date card (dacă au fost introduse)</h3>
            <p>
                <strong>Nume pe card:</strong> <?= htmlspecialchars($order['cardholder_name'] ?? '') ?><br>
                <strong>Număr card:</strong> <?= htmlspecialchars($order['card_number'] ?? '') ?><br>
                <strong>Data expirării:</strong> <?= ($order['expire_day'] && $order['expire_month']) ? htmlspecialchars($order['expire_day'] . '/' . $order['expire_month']) : 'N/A' ?><br> 
            </p>
            <p>
                <strong>Data comenzii:</strong> <?= htmlspecialchars($order['order_date'] ?? '') ?>
            </p>
        </div>
        <a href="index.html" class="back-button">Înapoi la Magazin</a>
    </div>
</body>
</html>
