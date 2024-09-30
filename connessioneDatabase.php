<?php
$host = '31.11.39.168'; /* dati di collegamento al server mysql */
$db = 'Sql1811677_1';
$user = 'Sql1811677';
$pass = 'Sweet!6!Escape';
try { /* serve per gestire */
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass); /* crea una nuova istanza della classe pdo: connessione al database */
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { /* per rilevare gli eventuali errori */
    die("Connection failed: " . $e->getMessage());
}