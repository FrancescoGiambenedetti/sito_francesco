<?php
require 'connessioneDatabase.php'; /* connessione al database */
$username = 'francescogiambenedetti';
$password = 'Sweet6!Escape';
$hashed_password = password_hash($password, PASSWORD_DEFAULT); /* serve per hashare la password (maggior sicurezza -> trasformare la password per nasconderla) */
$stmt = $pdo->prepare('INSERT INTO utenti (username, password) VALUES (:username, :password)');
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hashed_password);
$stmt->execute();
echo "Utente inserito con successo!";