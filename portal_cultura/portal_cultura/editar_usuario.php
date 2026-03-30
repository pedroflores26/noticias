<?php
// editar_usuario.php
require_once 'verifica_login.php';
require_once 'conexao.php';

$user  = usuarioLogado();
$stmt  = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$user['id']]);
$dados = $stmt->fetch();

$erro = $ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha']      ?? '';
    $conf  = $_POST['confirma']   ?? '';

    if (!$nome || !$email) {
        $erro = 'Nome e e-mail são obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif ($senha && $senha !== $conf) {
        $erro = 'As senhas não coincidem.';
    } elseif ($senha && mb_strlen($senha) < 6) {
        $erro = 'A senha deve ter no mínimo 6 caracteres.';
    } else {
        // Verifica e-mail duplicado (de outro usuário)
        $ck = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $ck->execute([$email, $user['id']]);
        if ($ck->fetch()) {
            $erro = 'Este e-mail já está em uso.';
        } else {
            if ($senha) {
                $hash = password_hash($senha, PASSWORD_BCRYPT);
                $upd  = $pdo->prepare("UPDATE usuarios SET nome=?, email=?, senha=? WHERE id=?");
                $upd->execute([$nome, $email, $hash, $user['id']]);
            } else {
                $upd = $pdo->prepare("UPDATE usuarios SET nome=?, email=? WHERE id=?");
                $upd->execute([$nome, $email, $user['id']]);
            }
            // Atualiza sessão
            $_SESSION['usuario_nome'] = $nome;
            $ok   = 'Dados atualizados com sucesso!';
            $dados = array_merge($dados, ['nome' => $nome, 'email' => $email]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minha Conta — Cultura &amp; Arte</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">Cultura <span>&amp;</span> Arte</a>
        <nav>
            <a href="dashboard.php">← Painel</a>
            <a href="logout.php">Sair</a>
        </nav>
    </div>
</header>

<main>
    <div class="form-box">
        <h2>👤 Minha Conta</h2>

        <?php if ($erro): ?>
            <div class="msg msg-erro"><?= limpar($erro) ?></div>
        <?php elseif ($ok): ?>
            <div class="msg msg-ok"><?= limpar($ok) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="nome">Nome completo</label>
                <input type="text" id="nome" name="nome"
                       value="<?= limpar($dados['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email"
                       value="<?= limpar($dados['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="senha">Nova senha <small style="font-weight:400;text-transform:none">(deixe vazio para manter)</small></label>
                <input type="password" id="senha" name="senha" placeholder="Nova senha (opcional)">
            </div>
            <div class="form-group">
                <label for="confirma">Confirmar nova senha</label>
                <input type="password" id="confirma" name="confirma" placeholder="Repita a nova senha">
            </div>
            <div style="display:flex;gap:.8rem;flex-wrap:wrap;margin-top:.5rem">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> <span>Cultura &amp; Arte</span></p>
</footer>

</body>
</html>
