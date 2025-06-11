<?php
session_start();
include 'db_connection.php';

if (isset($_POST['submit'])) {
    $Email = mysqli_real_escape_string($conn, $_POST['Email']);
    $Parola = mysqli_real_escape_string($conn, $_POST['Parola']);
    
    $result = mysqli_query($conn, "SELECT * FROM utilizatori WHERE Email='$Email'") or die("A apărut o eroare");
    $row = mysqli_fetch_assoc($result);

    // Verifică dacă există utilizatorul și dacă parola corespunde
    if ($row && $row['Parola'] == $Parola) {
        $_SESSION['valid'] = $row['Email'];
        $_SESSION['NumeUtilizator'] = $row['NumeUtilizator'];
        $_SESSION['Varsta'] = $row['Varsta'];
        $_SESSION['Id'] = $row['Id'];
        $_SESSION['Nume'] = $row['Nume'];         
        $_SESSION['Prenume'] = $row['Prenume'];    

        // Salvează rolul utilizatorului în sesiune
        $_SESSION['role'] = $row['role']; // presupunând că ai adăugat această coloană în baza de date

        // Setează rolul în localStorage pentru utilizare pe client
        echo "<script>localStorage.setItem('role', '" . $row['role'] . "');</script>";

        // Verifică rolul utilizatorului și redirecționează
        if ($_SESSION['role'] === 'admin') {
            header("Location: admin_dashboard.php"); // accesează dashboard-ul admin
        } else {
            header("Location: contulMeu.php"); // accesează pagina standard
        }
        exit;
    } else {
        $error = "Email sau parola incorectă";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logare</title>
    <link rel="stylesheet" href="ANTICARIAT/style.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</head>
<body class="login-body">
   <div class="login-container">
      <?php
         // Afișează mesajul de eroare dacă există
         if (isset($error)) {
             echo "<div class='message'><p>$error</p></div><br>";
                        
            // După autentificare cu succes
            echo "<script>localStorage.setItem('role', '" . $row['role'] . "');</script>";


         }
      ?>
      <div class="login-box form-box">
          <header>Logare</header>
          <form action="" method="POST">
              <div class="field input">
                  <label for="Email">Email</label>
                  <input type="text" id="Email" name="Email" required>
              </div>
              <div class="field input">
                  <label for="password">Parola</label>
                  <input type="password" id="password" name="Parola" required>
              </div>
              <div class="field">
                  <input type="submit" class="log-btn" id="submit" name="submit" value="Logare">
              </div>
              <div class="links">
                  Nu ai un cont? <a href="register.php">Înregistrează-te</a><br>
                  Intoarce-te la <a href="ANTICARIAT/index.html">pagina principală</a>
              </div>
          </form>
      </div>
   </div>
</body>
</html>
