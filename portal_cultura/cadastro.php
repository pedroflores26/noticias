<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'funcoes.php';
require_once 'conexao.php';

$erro = '';
$ok   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!$nome || !$email) {
        $erro = 'Preencha todos os campos.';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    }
    else {
        // verifica se já existe
        $st = $pdo->prepare("SELECT id FROM usuarios WHERE email=?");
        $st->execute([$email]);

        if ($st->fetch()) {
            $erro = 'Este e-mail já está cadastrado.';
        } else {

            // 🔐 senha padrão
            $senhaPadrao = password_hash('123456', PASSWORD_DEFAULT);

            // cadastra como pendente COM senha
            $pdo->prepare("
                INSERT INTO usuarios (nome,email,senha,tipo,status) 
                VALUES (?,?,?,'reporter','pendente')
            ")->execute([$nome, $email, $senhaPadrao]);

            $ok = 'Cadastro realizado! Aguarde aprovação do administrador.';
        }
    }
}

$pageTitle = 'Cadastro';
include '_header.php';
?>

<div class="page-form">
    <div class="form-card">
        <div class="form-top">
            <span class="form-brand">Ver<em>nissage</em></span>
            <h2>Criar Conta</h2>
            <p>Solicite acesso como reporter</p>
        </div>

        <?php if ($erro): ?>
            <div class="alert alert-err"><?= limpar($erro) ?></div>
        <?php endif; ?>

        <?php if ($ok): ?>
            <div class="alert alert-ok"><?= limpar($ok) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="field">
                <label>Nome</label>
                <input type="text" name="nome" required>
            </div>

            <div class="field">
                <label>E-mail</label>
                <input type="email" name="email" required>
            </div>

            <button type="submit" class="btn btn-accent">Solicitar acesso</button>
        </form>

        <p style="margin-top:1rem">
            Já tem conta? <a href="login.php">Entrar</a>
        </p>
    </div>
</div>

<?php include '_footer.php'; ?>