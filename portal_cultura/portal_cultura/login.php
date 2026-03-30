<?php
// login.php
session_start();
require_once 'conexao.php';
require_once 'funcoes.php';

if (estaLogado()) redirecionar('dashboard.php');

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$email || !$senha) {
        $erro = 'Preencha todos os campos.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            logarUsuario($usuario);
            redirecionar('dashboard.php');
        } else {
            $erro = 'E-mail ou senha incorretos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar — Cultura &amp; Arte</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">Cultura <span>&amp;</span> Arte</a>
        <nav><a href="index.php">← Início</a></nav>
    </div>
</header>

<main>
    <div class="form-box">
        <h2>🎭 Entrar</h2>

        <?php if ($erro): ?>
            <div class="msg msg-erro"><?= limpar($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email"
                       value="<?= limpar($_POST['email'] ?? '') ?>"
                       placeholder="seu@email.com" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:.5rem">Entrar</button>
        </form>

        <p style="text-align:center;margin-top:1.2rem;font-size:.9rem;color:var(--cinza)">
            Não tem conta? <a href="cadastro.php" style="color:var(--vinho);font-weight:700">Cadastre-se</a>
        </p>
    </div>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> <span>Cultura &amp; Arte</span></p>
</footer>

</body>
</html>
