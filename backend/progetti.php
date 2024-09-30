<?php
session_start();
require '../connessioneDatabase.php';
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['img_principale']) && isset($_FILES['img_card'])) {
    $uploadDir = '../img/progetti/';
    $imgPrincipaleName = basename($_FILES['img_principale']['name']);
    $imgCardName = basename($_FILES['img_card']['name']);
    $imgPrincipalePath = $uploadDir . $imgPrincipaleName;
    $imgCardPath = $uploadDir . $imgCardName;
    $allowedTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (
        in_array(strtolower(pathinfo($imgPrincipalePath, PATHINFO_EXTENSION)), $allowedTypes) &&
        in_array(strtolower(pathinfo($imgCardPath, PATHINFO_EXTENSION)), $allowedTypes)
    ) {
        if (
            move_uploaded_file($_FILES['img_principale']['tmp_name'], $imgPrincipalePath) &&
            move_uploaded_file($_FILES['img_card']['tmp_name'], $imgCardPath)
        ) {
            $titoloPrincipale = $_POST['titolo_principale'];
            $testoPrincipale = $_POST['testo_principale'];
            $titoloImgPrincipale = $_POST['titolo_img_principale'];
            $alternativaImgPrincipale = $_POST['alternativa_img_principale'];
            $titoloCard = $_POST['titolo_card'];
            $testoCard = $_POST['testo_card'];
            $titoloImgCard = $_POST['titolo_img_card'];
            $alternativaImgCard = $_POST['alternativa_img_card'];
            $data = $_POST['data'];
            $cliente = $_POST['cliente'];
            $programmi = $_POST['programmi'];
            $link = $_POST['link'];
            $stmt = $pdo->prepare('INSERT INTO projects (titolo_principale, testo_principale, img_principale, titolo_img_principale, alternativa_img_principale, titolo_card, testo_card, img_card, titolo_img_card, alternativa_img_card, data, cliente, programmi, link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$titoloPrincipale, $testoPrincipale, $imgPrincipaleName, $titoloImgPrincipale, $alternativaImgPrincipale, $titoloCard, $testoCard, $imgCardName, $titoloImgCard, $alternativaImgCard, $data, $cliente, $programmi, $link]);
            $message = "";
        } else {
            $message = "";
        }
    } else {
        $message = "";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id'])) {
    $stmt = $pdo->prepare('SELECT img_principale, img_card FROM projects WHERE id = ?');
    $stmt->execute([$_POST['project_id']]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($project) {
        $imgPrincipaleToDelete = '../img/progetti/' . $project['img_principale'];
        $imgCardToDelete = '../img/progetti/' . $project['img_card'];
        if (file_exists($imgPrincipaleToDelete)) {
            unlink($imgPrincipaleToDelete);
        }
        if (file_exists($imgCardToDelete)) {
            unlink($imgCardToDelete);
        }
        $stmt = $pdo->prepare('DELETE FROM projects WHERE id = ?');
        $stmt->execute([$_POST['project_id']]);
        $message = "";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_project_id'])) {
    $updateId = $_POST['update_project_id'];
    $titoloPrincipale = $_POST['titolo_principale'];
    $testoPrincipale = $_POST['testo_principale'];
    $titoloImgPrincipale = $_POST['titolo_img_principale'];
    $alternativaImgPrincipale = $_POST['alternativa_img_principale'];
    $titoloCard = $_POST['titolo_card'];
    $testoCard = $_POST['testo_card'];
    $data = $_POST['data'];
    $cliente = $_POST['cliente'];
    $programmi = $_POST['programmi'];
    $link = $_POST['link'];

    $stmt = $pdo->prepare('UPDATE projects SET titolo_principale = ?, testo_principale = ?, titolo_img_principale = ?, alternativa_img_principale = ?, titolo_card = ?, testo_card = ?, data = ?, cliente = ?, programmi = ?, link = ? WHERE id = ?');
    $stmt->execute([$titoloPrincipale, $testoPrincipale, $titoloImgPrincipale, $alternativaImgPrincipale, $titoloCard, $testoCard, $data, $cliente, $programmi, $link, $updateId]);
    $message = "";
}
$stmt = $pdo->prepare('SELECT * FROM projects');
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progetti</title>
    <link rel="stylesheet" href="style.css" title="foglio di stile css">
    <link href="../img/favicon/favicon.png" title="Favicon sito" rel="icon" type="image/x-icon">
</head>

<body>
    <div class="nav-menu">
        <a href="utenti.php">Utenti</a>
        <a href="navbar.php">Navbar</a>
        <a href="carosello.php">Carosello</a>
        <a href="skills.php">Skills</a>
        <a href="video.php">Video</a>
        <a href="progetti.php" class="active">Progetti</a>
    </div>
    <div class="containerGenerale">
        <h1>Progetti</h1>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="progetti.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="img_principale" required id="file-upload-2">
            <label for="file-upload-2" class="custom-file-upload" title="Seleziona immagine principale">[ ]</label>
            <input type="file" name="img_card" required id="file-upload-3">
            <label for="file-upload-3" class="custom-file-upload" title="Seleziona immagine card">( )</label>
            <input type="text" name="titolo_principale" placeholder="Titolo principale" required>
            <input type="text" name="testo_principale" placeholder="Testo principale" required>
            <input type="text" name="titolo_img_principale" placeholder="Titolo immagine principale" required>
            <input type="text" name="alternativa_img_principale" placeholder="Alternativa immagine principale" required>
            <input type="text" name="titolo_card" placeholder="Titolo card" required>
            <input type="text" name="testo_card" placeholder="Testo card" required>
            <input type="text" name="titolo_img_card" placeholder="Titolo immagine card" required>
            <input type="text" name="alternativa_img_card" placeholder="Alternativa immagine card" required>
            <input type="date" name="data" required>
            <input type="text" name="cliente" placeholder="Cliente" required>
            <input type="text" name="programmi" placeholder="Programmi utilizzati" required>
            <input type="text" name="link" placeholder="Link" required>
            <button type="submit" class="green" title="Aggiungi">+</button>
        </form>
        <div class="carousel-container">
            <?php if (count($projects) > 0): ?>
                <?php foreach ($projects as $project): ?>
                    <div class="carousel-item" style="margin-bottom: 20px;">
                        <h2><?php echo htmlspecialchars($project['titolo_principale']); ?></h2>
                        <div style="display: flex; align-items: center;">
                            <img src="../img/progetti/<?php echo htmlspecialchars($project['img_principale']); ?>" alt="<?php echo htmlspecialchars($project['titolo_img_principale']); ?>" title="<?php echo htmlspecialchars($project['titolo_img_principale']); ?>" style="max-width: 150px; margin-right: 20px;">
                            <img src="../img/progetti/<?php echo htmlspecialchars($project['img_card']); ?>" alt="<?php echo htmlspecialchars($project['titolo_img_card']); ?>" title="<?php echo htmlspecialchars($project['titolo_img_card']); ?>" style="max-width: 150px;">
                        </div>
                        <p id="scrittaProgetti"><?php echo htmlspecialchars($project['ultima_modifica']); ?></p>
                        <div style="display: flex; gap: 10px;">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                                <button type="submit" class="red" title="Elimina">x</button>
                            </form>
                            <button onclick="document.getElementById('editForm<?php echo $project['id']; ?>').style.display='block'" class="blue" title="Modifica">...</button>
                            <div id="editForm<?php echo $project['id']; ?>" style="display:none;">
                                <form action="progetti.php" method="POST">
                                    <input type="hidden" name="update_project_id" value="<?php echo $project['id']; ?>">
                                    <input type="text" name="titolo_principale" placeholder="Titolo principale" value="<?php echo htmlspecialchars($project['titolo_principale']); ?>" required>
                                    <input type="text" name="testo_principale" placeholder="Testo principale" value="<?php echo htmlspecialchars($project['testo_principale']); ?>" required>
                                    <input type="text" name="titolo_img_principale" placeholder="Titolo immagine principale" value="<?php echo htmlspecialchars($project['titolo_img_principale']); ?>" required>
                                    <input type="text" name="alternativa_img_principale" placeholder="Alternativa immagine principale" value="<?php echo htmlspecialchars($project['alternativa_img_principale']); ?>" required>
                                    <input type="text" name="titolo_card" placeholder="Titolo card" value="<?php echo htmlspecialchars($project['titolo_card']); ?>" required>
                                    <input type="text" name="testo_card" placeholder="Testo card" value="<?php echo htmlspecialchars($project['testo_card']); ?>" required>
                                    <input type="text" name="titolo_img_card" placeholder="Titolo immagine card" value="<?php echo htmlspecialchars($project['titolo_img_card']); ?>" required>
                                    <input type="text" name="alternativa_img_card" placeholder="Alternativa immagine card" value="<?php echo htmlspecialchars($project['alternativa_img_card']); ?>" required>
                                    <input type="date" name="data" value="<?php echo htmlspecialchars($project['data']); ?>" required>
                                    <input type="text" name="cliente" placeholder="Cliente" value="<?php echo htmlspecialchars($project['cliente']); ?>" required>
                                    <input type="text" name="programmi" placeholder="Programmi utilizzati" value="<?php echo htmlspecialchars($project['programmi']); ?>" required>
                                    <input type="text" name="link" placeholder="Link" value="<?php echo htmlspecialchars($project['link']); ?>" required>
                                    <button type="submit" class="green" title="Aggiorna">+</button>
                                    <button type="button" onclick="document.getElementById('editForm<?php echo $project['id']; ?>').style.display='none'" class="red" title="Annulla">x</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p></p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>