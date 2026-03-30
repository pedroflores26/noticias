<?php
// conexao.php – Conexão com o banco de dados

$host   = 'localhost';
$dbname = 'portal_cultura';
$user   = 'root';
$pass   = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('<p style="color:red;font-family:sans-serif;padding:2rem;">
        Erro ao conectar ao banco de dados: ' . $e->getMessage() . '
    </p>');
}
