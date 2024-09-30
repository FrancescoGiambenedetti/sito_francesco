<link href="../img/favicon/favicon.png" title="Favicon sito" rel="icon" type="image/x-icon">
<?php
session_start();
require '../connessioneDatabase.php';
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login.php');
    exit;
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_header'])) {
    $titolo = $_POST['titolo'];
    $link = $_POST['link'];
    $title = $_POST['title'];
    if (!empty($_POST['header_id'])) {
        $stmt = $pdo->prepare('UPDATE navbar_links SET etichetta = ?, link = ?, title = ? WHERE id = ?');
        if ($stmt->execute([$titolo, $link, $title, $_POST['header_id']])) {
            $message = "";
        } else {
            $message = "";
        }
    } else {
        $stmt = $pdo->prepare('INSERT INTO navbar_links (etichetta, link, title) VALUES (?, ?, ?)');
        if ($stmt->execute([$titolo, $link, $title])) {
            $message = "";
        } else {
            $message = "";
        }
    }
}
if (isset($_GET['delete_header'])) {
    $stmt = $pdo->prepare('DELETE FROM navbar_links WHERE id = ?');
    if ($stmt->execute([$_GET['delete_header']])) {
        $message = "";
    } else {
        $message = "";
    }
}
$stmt = $pdo->prepare('SELECT * FROM navbar_links');
$stmt->execute();
$header_voci = $stmt->fetchAll(PDO::FETCH_ASSOC);
$header_edit = null;
if (isset($_GET['edit_header'])) {
    $stmt = $pdo->prepare('SELECT * FROM navbar_links WHERE id = ?');
    $stmt->execute([$_GET['edit_header']]);
    $header_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (isset($_GET['new'])) {
    $header_edit = null;
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voci navbar</title>
    <link rel="stylesheet" href="style.css" title="foglio di stile css">
</head>

<body>
    <div class="nav-menu">
        <a href="utenti.php">Utenti</a>
        <a href="navbar.php" class="active">Navbar</a>
        <a href="carosello.php">Carosello</a>
        <a href="skills.php">Skills</a>
        <a href="video.php">Video</a>
        <a href="progetti.php">Progetti</a>
    </div>
    <div class="containerGenerale">
        <h1>Voci navbar</h1>
        <?php if (isset($message)): ?>
            <p class="success"><?php echo $message; ?></p>
        <?php endif; ?>
        <div class="tab-content">
            <form method="POST">
                <input type="hidden" name="header_id" value="<?php echo isset($header_edit) ? $header_edit['id'] : ''; ?>">
                <input type="text" name="titolo" placeholder="Nome" value="<?php echo isset($header_edit) ? $header_edit['etichetta'] : ''; ?>" required>
                <input type="text" name="link" placeholder="Link" value="<?php echo isset($header_edit) ? $header_edit['link'] : ''; ?>" required>
                <input type="text" name="title" placeholder="Titolo" value="<?php echo isset($header_edit) ? $header_edit['title'] : ''; ?>" required>
                <button type="submit" name="submit_header" class="green" title="Aggiungi">+</button>
            </form>
        </div>
        <div class="tab-content">
            <ul>
                <?php foreach ($header_voci as $header): ?>
                    <li>
                        <strong><?php echo $header['etichetta']; ?></strong> - <?php echo $header['link']; ?> - <?php echo $header['title']; ?>
                        <br>
                        <?php echo $header['last_modified']; ?>
                        <br>
                        <div class="navbarButton">
                            <a href="?delete_header=<?php echo $header['id']; ?>" onclick="return confirm('Sei sicuro di voler eliminare questa voce?')" class="red" title="Elimina">x</a>
                            <a href="?edit_header=<?php echo $header['id']; ?>" class="blue" title="Modifica">...</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>

</html>