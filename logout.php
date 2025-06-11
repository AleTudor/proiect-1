<?php
session_start();
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Delogare</title>
    <meta http-equiv="refresh" content="1;url=login.php">
</head>
<body>
    <script>
        // Curățăm localStorage pentru a elimina vechiul rol
        localStorage.removeItem('role'); // sau localStorage.clear(); dacă vrei tot
    </script>
    <p>Te-ai delogat. Vei fi redirecționat către pagina de login...</p>
</body>
</html>

