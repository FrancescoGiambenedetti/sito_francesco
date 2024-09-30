<link rel="stylesheet" href="css/style.min.css" title="foglio di stile css">
<link href="./img/favicon/favicon.png" title="Favicon sito" rel="icon" type="image/x-icon">
<?php
session_start();
require 'connessioneDatabase.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { /* verifica che la richiesta sia post (dati inviati solo quando il form viene effettivamente inviato) */
    $username = $_POST['username']; /* recupera username inserito */
    $password = $_POST['password']; /* reupera password inserita */
    $stmt = $pdo->prepare('SELECT * FROM utenti WHERE username = ?'); /* prepara una query per selezionare l'utente con lo username corrispondente a quello inserito */
    $stmt->execute([$username]); /* il "?" viene sostituito con l'username inserito */
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) { /* se esiste un utente nel database con quei dati, la variabile "$user" prenderà i dati; viene verificata la password hashata corrispondente */
        $_SESSION['loggedin'] = true; /* login con successo (utente e password hashata corrispondono) */
        header('Location: backend/navbar.php'); /* destinazione del login */
        exit;
    } else {
        $error = "";
    }
}
?>
<header class="header">
    <div class="logo">
        <img src="img/logo/logo.png" title="Logo" alt="Logo" class="img-logo">
    </div>
    <div class="loginForm">
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Username" required class="loginInput">
            <input type="password" name="password" placeholder="Password" required class="loginInput">
            <?php if (isset($error)): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <button type="submit" class="inviaForm-button" title="Invia form">Invia</button>
        </form>
    </div>
</header>