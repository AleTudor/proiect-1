<!DOCTYPE html>
<html> 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inregistrare</title>
    <link rel="stylesheet" href="ANTICARIAT/style.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</head>
<body class="login-body">




<?php
include 'db_connection.php';
if(isset($_POST['submit'])){
    $NumeUtilizator = $_POST['NumeUtilizator'];
    $Email= $_POST['Email'];
    $Varsta= $_POST['Varsta'];
    $Parola= $_POST['Parola'];
    $Nume= $_POST['Nume'];
    $Prenume= $_POST['Prenume'];
  
 //verificam parola unica

 $verify = mysqli_query($conn, "SELECT Email FROM utilizatori WHERE Email='$Email'");
if (mysqli_num_rows($verify) != 0) {

     echo "<div class='message-container'>  
     <div class='message'>
     <p>Email-ul acesta este deja folosit, incercati altul</p>
     </div> <br>";
     echo "<a href='javascript:self.history.back()'><button class='log-btn1'> Inapoi </button>  </div>";
 }
 else {
    mysqli_query($conn, "INSERT INTO utilizatori(NumeUtilizator, Email, Varsta, Parola, Nume, Prenume) VALUES ('$NumeUtilizator', '$Email', '$Varsta', '$Parola', '$Nume', '$Prenume')")or die("A aparut o eroare");    

    echo "<div class='message-container'> 
    <div class='message'>
    <p>V-ati inregistrat cu succes!</p>
    </div> <br>";
     echo"<a href='login.php'><button class='log-btn1'> Logati-va </button> </div>";
}

} else{
?>





   <div class="login-container">
    <div class="login-box form-box">
        <header>Inregistreaza-te</header>
        <form action=""  method="POST">
            <div class="field input">
                <label for="username">Nume utilizator</label>
                <input type="text" id="username" name="NumeUtilizator" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="username">Nume real</label>
                <input type="text" id="name" name="Nume" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="username">Prenume real</label>
                <input type="text" id="prename" name="Prenume" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="Email">Email</label>
                <input type="Email" id="Email" name="Email" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="age">Varsta</label>
                <input type="number" id="age" name="Varsta" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="password">Parola</label>
                <input type="password" id="password" name="Parola" autocomplete="off" required>
            </div>

            <div class="field">
                
                <input type="submit" class="log-btn" id="submit" name="submit" value="Inregistrare" required>
            </div>

            <div class="links">
                Ai deja cont? <a href="login.php">Conecteaza-te</a>
        </form>
    </div>
    <?php } ?>
   </div>
</body>
</html>