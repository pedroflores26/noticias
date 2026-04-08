<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'funcoes.php';
require_once 'conexao.php';

if (!logado()) {
    ir('login.php');
}

$me = sessaoUser();

$st = $pdo->prepare("SELECT * FROM usuarios WHERE id=?");
$st->execute([$me['id']]);
$dados = $st->fetch();

$painel = ehAdmin() ? 'dashboard_admin.php' : 'dashboard_reporter.php';
$erro = $ok = '';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha']      ?? '';
    $conf  = $_POST['confirma']   ?? '';

    if (!$nome || !$email) { 
        $erro = 'Nome e e-mail são obrigatórios.'; 
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        $erro = 'E-mail inválido.'; 
    }
    elseif ($senha && $senha !== $conf) { 
        $erro = 'As senhas não coincidem.'; 
    }
    elseif ($senha && mb_strlen($senha) < 6) { 
        $erro = 'Senha: mínimo 6 caracteres.'; 
    }
    else {
        $ck = $pdo->prepare("SELECT id FROM usuarios WHERE email=? AND id!=?");
        $ck->execute([$email, $me['id']]);

        if ($ck->fetch()) { 
            $erro = 'Este e-mail já está em uso.'; 
        } else {

            if ($senha) {
                $pdo->prepare("UPDATE usuarios SET nome=?,email=?,senha=? WHERE id=?")
                    ->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT), $me['id']]);
            } else {
                $pdo->prepare("UPDATE usuarios SET nome=?,email=? WHERE id=?")
                    ->execute([$nome, $email, $me['id']]);
            }

            $_SESSION['unome'] = $nome;
            $ok = 'Perfil atualizado com sucesso!';
            $dados = array_merge($dados, ['nome'=>$nome,'email'=>$email]);
        }
    }
}

$pageTitle = 'Meu Perfil';
$backLink  = [$painel, 'Painel'];
include '_header.php';
?>

<div class="page-form">
    <div class="form-card">
        <div class="form-top">
            <span class="form-brand">Ver<em>nissage</em></span>
            <h2>Meu Perfil</h2>
            <p>Edite seus dados de acesso</p>
        </div>

        <?php if ($erro): ?><div class="alert alert-err"><?= limpar($erro) ?></div><?php endif; ?>
        <?php if ($ok): ?><div class="alert alert-ok"><?= limpar($ok) ?></div><?php endif; ?>

        <form method="POST">
            <div class="field">
                <label>Nome completo</label>
                <input type="text" name="nome" value="<?= limpar($dados['nome']) ?>" required>
            </div>
            <div class="field">
                <label>E-mail</label>
                <input type="email" name="email" value="<?= limpar($dados['email']) ?>" required>
            </div>
            <div class="field">
                <label>Nova senha</label>
                <input type="password" name="senha" placeholder="Nova senha (opcional)">
            </div>
            <div class="field">
                <label>Confirmar nova senha</label>
                <input type="password" name="confirma" placeholder="Repita a nova senha">
            </div>
            <div style="display:flex;gap:.7rem;flex-wrap:wrap">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="<?= $painel ?>" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include '_footer.php'; ?>