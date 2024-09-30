<link href="../img/favicon/favicon.png" title="Favicon sito" rel="icon" type="image/x-icon">
<?php
session_start();
require '../connessioneDatabase.php';
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login.php');
    exit;
}
$stmt = $pdo->prepare('SELECT * FROM skills');
$stmt->execute();
$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
function getBarColor($level)
{
    if ($level <= 25) {
        return 'rgb(244, 67, 54)';
    } elseif ($level <= 50) {
        return 'rgb(255, 152, 0)';
    } elseif ($level <= 75) {
        return 'rgb(255, 235, 59)';
    } else {
        return 'rgb(76, 175, 80)';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_skill'])) {
    $skillName = $_POST['skill_name'];
    $skillLevel = min(max((int)$_POST['skill_level'], 0), 100);
    $stmt = $pdo->prepare('INSERT INTO skills (skill_name, skill_level, last_modified) VALUES (?, ?, NOW())');
    $stmt->execute([$skillName, $skillLevel]);
    header('Location: skills.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_skill'])) {
    $skillId = $_POST['skill_id'];
    $stmt = $pdo->prepare('DELETE FROM skills WHERE id = ?');
    $stmt->execute([$skillId]);
    header('Location: skills.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_skill'])) {
    $skillId = $_POST['skill_id'];
    $skillLevel = min(max((int)$_POST['skill_level'], 0), 100);
    $stmt = $pdo->prepare('UPDATE skills SET skill_level = ?, last_modified = NOW() WHERE id = ?');
    $stmt->execute([$skillLevel, $skillId]);
    header('Location: skills.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills</title>
    <link rel="stylesheet" href="style.css" title="foglio di stile css">
</head>

<body>
    <div class="nav-menu">
        <a href="utenti.php">Utenti</a>
        <a href="navbar.php">Navbar</a>
        <a href="carosello.php">Carosello</a>
        <a href="skills.php" class="active">Skills</a>
        <a href="video.php">Video</a>
        <a href="progetti.php">Progetti</a>
    </div>
    <div class="containerGenerale">
        <h1>Skills</h1>
        <div class="add-skill">
            <form method="POST">
                <input type="text" name="skill_name" placeholder="Nome" required>
                <input type="number" name="skill_level" min="0" max="100" placeholder="0-100" required>
                <button class="green" type="submit" name="add_skill" title="Aggiungi">+</button>
            </form>
        </div>
        <div class="skills-section">
            <div id="skillsContainer">
                <?php foreach ($skills as $skill): ?>
                    <div class="skill">
                        <p id="skillTitle"><?php echo htmlspecialchars($skill['skill_name']); ?></p>
                        <p class="scrittaSkills"><?php echo $skill['skill_level']; ?> / 100</p>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: <?php echo $skill['skill_level']; ?>%; background-color: <?php echo getBarColor($skill['skill_level']); ?>;"></div>
                        </div>
                        <p class="scrittaSkills"><?php echo htmlspecialchars($skill['last_modified']); ?></p>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="skill_id" value="<?php echo $skill['id']; ?>">
                            <button type="submit" name="remove_skill" class="red" title="Elimina">x</button>
                        </form>
                        <button class="blue" onclick="document.getElementById('updateSkillForm<?php echo $skill['id']; ?>').style.display='block'" title="Modifica">...</button>
                        <div id="updateSkillForm<?php echo $skill['id']; ?>" style="display:none;">
                            <form method="POST">
                                <input type="hidden" name="skill_id" value="<?php echo $skill['id']; ?>">
                                <input type="number" name="skill_level" min="0" max="100" placeholder="0-100" required>
                                <button type="submit" name="update_skill" class="green" title="Aggiorna">+</button>
                                <button type="button" onclick="document.getElementById('updateSkillForm<?php echo $skill['id']; ?>').style.display='none'" class="red" title="Annulla">x</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>

</html>