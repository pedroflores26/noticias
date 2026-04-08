<?php
require_once 'verifica_admin.php';
require_once 'conexao.php';

$id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT)
   ?: filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if (!$id) ir('dashboard_admin.php');

$st = $pdo->prepare("SELECT * FROM usuarios WHERE id=?");
$st->execute([$id]);
$u = $st->fetch();
if (!$u) ir('dashboard_admin.php');

$erro = $ok = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $tipo  = in_array($_POST['tipo']??'',['admin','reporter']) ? $_POST['tipo'] : 'reporter';
    $senha = $_POST['senha'] ?? '';

    if (!$nome||!$email) { $erro='Nome e e-mail são obrigatórios.'; }
    elseif (!filter_var($email,FILTER_VALIDATE_EMAIL)) { $erro='E-mail inválido.'; }
    elseif ($senha && mb_strlen($senha)<6) { $erro='Senha: mínimo 6 caracteres.'; }
    else {
        $ck=$pdo->prepare("SELECT id FROM usuarios WHERE email=? AND id!=?");
        $ck->execute([$email,$id]);
        if ($ck->fetch()) { $erro='E-mail já em uso por outro usuário.'; }
        else {
            if ($senha) {
                $pdo->prepare("UPDATE usuarios SET nome=?,email=?,tipo=?,senha=? WHERE id=?")
                    ->execute([$nome,$email,$tipo,password_hash($senha,PASSWORD_BCRYPT),$id]);
            } else {
                $pdo->prepare("UPDATE usuarios SET nome=?,email=?,tipo=? WHERE id=?")
                    ->execute([$nome,$email,$tipo,$id]);
            }
            $ok = 'Usuário atualizado com sucesso!';
            $u  = array_merge($u,['nome'=>$nome,'email'=>$email,'tipo'=>$tipo]);
        }
    }
}

$pageTitle = 'Editar Usuário';
$backLink  = ['dashboard_admin.php','Painel Admin'];
include '_header.php';
?>

<div class="page-form">
    <div class="form-card">
        <div class="form-top">
            <span class="form-brand">Ver<em>nissage</em></span>
            <h2>Editar Usuário</h2>
            <p><?= limpar($u['nome']) ?></p>
        </div>

        <?php if ($erro): ?><div class="alert alert-err"><?= limpar($erro) ?></div><?php endif; ?>
        <?php if ($ok):   ?><div class="alert alert-ok"><?= limpar($ok)  ?></div><?php endif; ?>

        <form method="POST">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="field">
                <label>Nome completo</label>
                <input type="text" name="nome" value="<?= limpar($u['nome']) ?>" required>
            </div>
            <div class="field">
                <label>E-mail</label>
                <input type="email" name="email" value="<?= limpar($u['email']) ?>" required>
            </div>
            <div class="field">
                <label>Tipo de acesso</label>
                <select name="tipo">
                    <option value="reporter" <?= $u['tipo']==='reporter'?'selected':'' ?>>Reporter</option>
                    <option value="admin"    <?= $u['tipo']==='admin'   ?'selected':'' ?>>Administrador</option>
                </select>
            </div>
            <div class="field">
                <label>Nova senha <small style="font-weight:300;text-transform:none">(deixe vazio para manter)</small></label>
                <input type="text" name="senha" placeholder="Nova senha (opcional)">
            </div>
            <div style="display:flex;gap:.7rem;flex-wrap:wrap">
                <button type="submit" class="btn btn-primary">Salvar alterações</button>
                <a href="dashboard_admin.php" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include '_footer.php'; ?>
