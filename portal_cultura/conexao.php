<?php
$host   = 'localhost';
$dbname = 'portal_cultura';
$user   = 'root';
$pass   = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    die('<p style="color:red;padding:2rem;font-family:sans-serif">Erro BD: '.$e->getMessage().'</p>');
}

?>