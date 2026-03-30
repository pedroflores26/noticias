<?php
// cadastro.php
session_start();
require_once 'conexao.php';
require_once 'funcoes.php';

if (estaLogado()) redirecionar('dashboard.php');

$erro = $ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha']      ?? '';
    $conf  = $_POST['confirma']   ?? '';

    if (!$nome || !$email || !$senha || !$conf) {
        $erro = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (mb_strlen($senha) < 6) {
        $erro = 'A senha deve ter no mínimo 6 caracteres.';
    } elseif ($senha !== $conf) {
        $erro = 'As senhas não coincidem.';
    } else {
        $ck = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $ck->execute([$email]);
        if ($ck->fetch()) {
            $erro = 'Este e-mail já está cadastrado.';
        } else {
            $hash = password_hash($senha, PASSWORD_BCRYPT);
            $ins  = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $ins->execute([$nome, $email, $hash]);
            $ok = 'Conta criada com sucesso! <a href="login.php" style="color:var(--vinho)">Faça login</a>.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro — Cultura &amp; Arte</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">Cultura <span>&amp;</span> Arte</a>
        <nav><a href="login.php">Já tenho conta</a></nav>
    </div>
</header>

<main>
    <div class="form-box">
        <h2>🎨 Criar Conta</h2>

        <?php if ($erro): ?>
            <div class="msg msg-erro"><?= limpar($erro) ?></div>
        <?php elseif ($ok): ?>
            <div class="msg msg-ok"><?= $ok ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="nome">Nome completo</label>
                <input type="text" id="nome" name="nome"
                       value="<?= limpar($_POST['nome'] ?? '') ?>"
                       placeholder="Seu nome" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email"
                       value="<?= limpar($_POST['email'] ?? '') ?>"
                       placeholder="seu@email.com" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Mín. 6 caracteres" required>
            </div>
            <div class="form-group">
                <label for="confirma">Confirmar senha</label>
                <input type="password" id="confirma" name="confirma" placeholder="Repita a senha" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:.5rem">Criar conta</button>
        </form>

        <p style="text-align:center;margin-top:1.2rem;font-size:.9rem;color:var(--cinza)">
            Já tem conta? <a href="login.php" style="color:var(--vinho);font-weight:700">Entrar</a>
        </p>
    </div>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> <span>Cultura &amp; Arte</span></p>
</footer>

</body>
</html>
