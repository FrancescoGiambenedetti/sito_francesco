<link href="../img/favicon/favicon.png" title="Favicon sito" rel="icon" type="image/x-icon">
<?php
session_start();
require '../connessioneDatabase.php'; /* richiesta connessione al dataase */
if (!isset($_SESSION['loggedin'])) { /* verifica che si è loggati */
    header('Location: ../login.php'); /* altrimenti si torna alla pagina di login */
    exit;
}
$uploadDir = '../img/carosello/'; /* posizione di riferimento per le immagini */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image_file'])) { /* verifica il metodo post e se è stato caricato un file */
    $fileName = basename($_FILES['image_file']['name']);
    $targetFilePath = $uploadDir . $fileName; /* crea il percorso di destinazione per l'immagine caricata */
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION)); /* consente di ottenere l'estensione del file caricato in minuscolo */
    $allowedTypes = array('jpg', 'png', 'jpeg', 'gif'); /* array per le estensioni accettate */
    if (in_array($imageFileType, $allowedTypes)) { /* controlla che l'estensione si tra quelle indicate */
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFilePath)) { /* se tutto è regolare, il file viene spostato */
            $alt = $_POST['alt']; /* recupero dei valori */
            $title = $_POST['title'];
            $stmt = $pdo->prepare('INSERT INTO carousel_images (src, alt, title, last_modified) VALUES (?, ?, ?, NOW())'); /* query per inserire valori nella tabella sql */
            $stmt->execute([$fileName, $alt, $title]);
            $message = ""; /* feedback positivo */
        } else {
            $message = ""; /* feedback se qualcosa non è andato */
        }
    } else {
        $message = "";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image_id'])) {
    $stmt = $pdo->prepare('SELECT src FROM carousel_images WHERE id = ?'); /* prepara una query sql per selezionare il campo "src */
    $stmt->execute([$_POST['image_id']]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($image) { /* verifica che l'immagine esiste */
        $fileToDelete = $uploadDir . $image['src']; /* percorso per eliminare l'immagine */
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }
        $stmt = $pdo->prepare('DELETE FROM carousel_images WHERE id = ?'); /* questo per eliminare */
        $stmt->execute([$_POST['image_id']]);
        $message = "";
    }
}
$stmt = $pdo->prepare('SELECT * FROM carousel_images'); /* query per selezionare tutte le immagini dalla tabella */
$stmt->execute(); /* esegue la query */
$images = $stmt->fetchAll(PDO::FETCH_ASSOC); /* recupera le immagini come array associativi */
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto carosello</title>
    <link rel="stylesheet" href="style.css" title="foglio di stile css">
</head>

<body>
    <div class="nav-menu">
        <a href="utenti.php">Utenti</a>
        <a href="navbar.php">Navbar</a>
        <a href="carosello.php" class="active">Carosello</a>
        <a href="skills.php">Skills</a>
        <a href="video.php">Video</a>
        <a href="progetti.php">Progetti</a>
    </div>
    <div class="containerGenerale">
        <h1>Foto carosello</h1>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="carosello.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="image_file" id="file-upload" required>
            <label for="file-upload" class="custom-file-upload" title="Seleziona immagine">[ ]</label>
            <input type="text" name="title" placeholder="Title" required>
            <input type="text" name="alt" placeholder="Alt" required>
            <button type="submit" class="green" title="Aggiungi">+</button>
        </form>
        <div class="carousel-container">
            <?php foreach ($images as $image): ?>
                <div class="carousel-item">
                    <img src="../img/carosello/<?php echo htmlspecialchars($image['src']); ?>" alt="<?php echo htmlspecialchars($image['alt']); ?>" title="<?php echo htmlspecialchars($image['title']); ?>">
                    <p id="scrittaCarosello">Data aggiunta: <?php echo htmlspecialchars($image['last_modified']); ?></p>
                    <form method="POST">
                        <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                        <button type="submit" class="red" title="Elimina">x</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>