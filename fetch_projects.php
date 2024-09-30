<?php /* usata tecnologia di comunicazione ajax (senza necessità di ricaricare la pagina) */
session_start();
require 'connessioneDatabase.php'; /* connessione al database */
if (isset($_GET['id'])) {
    $projectId = $_GET['id']; /* passato tramite url (get, variabile superglobale) l'id */
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ?'); /* prepara una query sql per selezionare un progetto dalla tabella "projects" in base al suo id */
    $stmt->execute([$projectId]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($project) {
        echo json_encode($project); /* se il progetto esiste, viene resistuito come json */
    } else {
        echo json_encode(['error' => 'Progetto non trovato']); /* messaggio se non esiste un progetto con l'id fornito */
    }
} else {
    echo json_encode(['error' => 'ID non fornito']); /* serve qualora non venga mandato l'id */
}
?>