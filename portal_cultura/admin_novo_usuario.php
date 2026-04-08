<?php
require_once 'verifica_admin.php';
require_once 'conexao.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha']      ?? '';
    $tipo  = in_array($_POST['tipo']??'', ['admin','reporter']) ? $_POST['tipo'] : 'reporter';

    if (!$nome||!$email||!$senha) { $erro='Preencha todos os campos.'; }
    elseif (!filter_var($email,FILTER_VALIDATE_EMAIL)) { $erro='E-mail inválido.'; }
    elseif (mb_strlen($senha)<6) { $erro='Senha: mínimo 6 caracteres.'; }
    else {
        $ck=$pdo->prepare("SELECT id FROM usuarios WHERE email=?"); $ck->execute([$email]);
        if ($ck->fetch()) { $erro='E-mail já cadastrado.'; }
        else {
            $pdo->prepare("INSERT INTO usuarios (nome,email,senha,tipo) VALUES (?,?,?,?)")
                ->execute([$nome,$email,password_hash($senha,PASSWORD_BCRYPT),$tipo]);
            ir('dashboard_admin.php');
        }
    }
}

$pageTitle = 'Novo Usuário';
$backLink  = ['dashboard_admin.php','Painel Admin'];
include '_header.php';
?>

<div class="page-form">
    <div class="form-card">
        <div class="form-top">
            <span class="form-brand">Ver<em>nissage</em></span>
            <h2>Novo Usuário</h2>
            <p>Criar conta de reporter ou administrador</p>
        </div>

        <?php if ($erro): ?><div class="alert alert-err"><?= limpar($erro) ?></div><?php endif; ?>

        <form method="POST">
            <div class="field">
                <label>Nome completo</label>
                <input type="text" name="nome" value="<?= limpar($_POST['nome']??'') ?>" placeholder="Nome do usuário" required>
            </div>
            <div class="field">
                <label>E-mail</label>
                <input type="email" name="email" value="<?= limpar($_POST['email']??'') ?>" placeholder="email@exemplo.com" required>
            </div>
            <div class="field">
                <label>Senha inicial</label>
                <input type="text" name="senha" placeholder="Mínimo 6 caracteres" required>
            </div>
            <div class="field">
                <label>Tipo de acesso</label>
                <select name="tipo">
                    <option value="reporter" <?= ($_POST['tipo']??'')==='reporter'?'selected':'' ?>>Reporter</option>
                    <option value="admin"    <?= ($_POST['tipo']??'')==='admin'   ?'selected':'' ?>>Administrador</option>
                </select>
            </div>
            <div style="display:flex;gap:.7rem;flex-wrap:wrap">
                <button type="submit" class="btn btn-accent">Criar usuário</button>
                <a href="dashboard_admin.php" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include '_footer.php'; ?>
