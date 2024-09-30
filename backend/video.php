<link href="../img/favicon/favicon.png" title="Favicon sito" rel="icon" type="image/x-icon">
<?php
session_start();
require '../connessioneDatabase.php';
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login.php');
    exit;
}
$uploadDir = '../video/';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video_file'])) {
    $fileName = basename($_FILES['video_file']['name']);
    $targetFilePath = $uploadDir . $fileName;
    $videoFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedTypes = array('mp4', 'avi', 'mov');
    if (in_array($videoFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['video_file']['tmp_name'], $targetFilePath)) {
            $alt = $_POST['alt'];
            $title = $_POST['title'];
            $stmt = $pdo->prepare('INSERT INTO videos (src, alt, title, last_modified) VALUES (?, ?, ?, NOW())');
            $stmt->execute([$fileName, $alt, $title]);
            $message = "";
        } else {
            $message = "";
        }
    } else {
        $message = "";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['video_id'])) {
    $stmt = $pdo->prepare('SELECT src FROM videos WHERE id = ?');
    $stmt->execute([$_POST['video_id']]);
    $video = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($video) {
        $fileToDelete = $uploadDir . $video['src'];
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }
        $stmt = $pdo->prepare('DELETE FROM videos WHERE id = ?');
        $stmt->execute([$_POST['video_id']]);
        $message = "";
    }
}
$stmt = $pdo->prepare('SELECT * FROM videos');
$stmt->execute();
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video progetto</title>
    <link rel="stylesheet" href="style.css" title="foglio di stile css">
</head>

<body>
    <div class="nav-menu">
        <a href="utenti.php">Utenti</a>
        <a href="navbar.php">Navbar</a>
        <a href="carosello.php">Carosello</a>
        <a href="skills.php">Skills</a>
        <a href="video.php" class="active">Video</a>
        <a href="progetti.php">Progetti</a>
    </div>
    <div class="containerGenerale">
        <h1>Video progetto</h1>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="video.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="video_file" id="video-upload" required>
            <label for="video-upload" class="custom-file-upload" title="Seleziona video">[ ]</label>
            <input type="text" name="title" placeholder="Titolo" required>
            <input type="text" name="alt" placeholder="Alternativa" required>
            <button type="submit" class="green" title="Aggiungi">+</button>
        </form>
        <div class="video-container">
            <?php foreach ($videos as $video): ?>
                <div class="video-item">
                    <video controls>
                        <source src="../video/<?php echo htmlspecialchars($video['src']); ?>" type="video/mp4">
                        Impossibile aprire il contenuto.
                    </video>
                    <p id="scrittaVideo"><?php echo htmlspecialchars($video['last_modified']); ?></p>
                    <form method="POST">
                        <input type="hidden" name="video_id" value="<?php echo $video['id']; ?>">
                        <button type="submit" class="red" title="Elimina">x</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>