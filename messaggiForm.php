<?php
session_start();
header('Content-Type: application/json');
require_once 'connessioneDatabase.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $data = [];
    $requiredFields = ['nome', 'cognome', 'email', 'argomento', 'oggetto', 'testo'];
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'Errore CSRF: richiesta non valida.']);
        exit;
    }
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = ucfirst($field) . " è obbligatorio";
        } else {
            $data[$field] = htmlspecialchars($_POST[$field]);
        }
    }
    if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Indirizzo email non valido";
    }
    if (strlen($data['testo']) > 100) {
        $errors['testo'] = "Il messaggio non può superare i 100 caratteri.";
    }
    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }
    try {
        $sql = "INSERT INTO contatti (nome, cognome, email, telefono, argomento, oggetto, messaggio, data_invio)
                VALUES (:nome, :cognome, :email, :telefono, :argomento, :oggetto, :messaggio, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $data['nome'],
            ':cognome' => $data['cognome'],
            ':email' => $data['email'],
            ':telefono' => isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : null,
            ':argomento' => $data['argomento'],
            ':oggetto' => $data['oggetto'],
            ':messaggio' => $data['testo']
        ]);
        class_mailer($data['email']);
        $to = "francesco.giambenedetti@gmail.com";
        $subject = "Nuovo messaggio dal form di contatto su francescogiambenedetti.it";
        $message = "Hai ricevuto un nuovo messaggio:\n\n";
        $message .= "Nome: {$data['nome']}\n";
        $message .= "Cognome: {$data['cognome']}\n";
        $message .= "Email: {$data['email']}\n";
        $message .= "Telefono: " . (isset($_POST['telefono']) ? $_POST['telefono'] : 'Non fornito') . "\n";
        $message .= "Argomento: {$data['argomento']}\n";
        $message .= "Oggetto: {$data['oggetto']}\n";
        $message .= "Messaggio: {$data['testo']}\n";
        $headers = "From: noreply@francescogiambenedetti.it" . "\r\n" .
            "Reply-To: {$data['email']}" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
        if (mail($to, $subject, $message, $headers)) {
            echo json_encode(['success' => true, 'message' => "Grazie, il tuo messaggio è stato inviato con successo!"]);
        } else {
            echo json_encode(['success' => false, 'message' => "Errore durante l'invio dell'email."]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "Errore durante l'invio del form: " . $e->getMessage()]);
    }
    exit;
}
function class_mailer($email)
{
    $to = 'francesco.giambenedetti@gmail.com';
    $subject = 'Notifica: nuovo messaggio dal sito';
    $message = "Visiona su Aruba-PHPMyAdmin il nuovo messaggio ricevuto tramite www.francescogiambenedetti.it da: $email.";
    $headers = 'From: noreply@francescogiambenedetti.it' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $message, $headers);
}