<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'funcoes.php';
require_once 'conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$email || !$senha) {
        $erro = 'Preencha todos os campos.';
    } else {
        $st = $pdo->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");
        $st->execute([$email]);
        $usuario = $st->fetch();

        if (!$usuario) {
            $erro = '❌ Email ou senha inválidos';
        } 
        elseif ($usuario['status'] !== 'ativo') {
            $erro = '⏳ Sua conta ainda não foi liberada pelo administrador.';
        }
        elseif (!password_verify($senha, $usuario['senha'])) {
            $erro = '❌ Email ou senha inválidos';
        }
        else {
            // ✅ inicia sessão
            iniciarSessao([
                'id'   => $usuario['id'],
                'nome' => $usuario['nome'],
                'tipo' => $usuario['tipo']
            ]);

            // 🔀 redireciona
            if ($usuario['tipo'] === 'admin') {
                ir('dashboard_admin.php');
            } else {
                ir('dashboard_reporter.php');
            }
        }
    }
}

$pageTitle = 'Login';
include '_header.php';
?>

<div class="page-form">
    <div class="form-card">
        <div class="form-top">
            <span class="form-brand">Ver<em>nissage</em></span>
            <h2>Entrar</h2>
            <p>Acesse sua conta</p>
        </div>

        <?php if ($erro): ?>
            <div class="alert alert-err"><?= limpar($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="field">
                <label>E-mail</label>
                <input type="email" name="email" required>
            </div>

            <div class="field">
                <label>Senha</label>
                <input type="password" name="senha" required>
            </div>

            <button type="submit" class="btn btn-accent">Entrar</button>
        </form>

        <p style="margin-top:1rem">
            Não tem conta? <a href="cadastro.php">Solicitar acesso</a>
        </p>
    </div>
</div>

<?php include '_footer.php'; ?>