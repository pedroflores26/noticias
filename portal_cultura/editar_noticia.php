<?php
require_once 'verifica_login.php';
require_once 'conexao.php';

$u  = sessaoUser();
$id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT)
   ?: filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if (!$id) ir('dashboard_reporter.php');

$st = $pdo->prepare("SELECT * FROM noticias WHERE id=? AND autor=?");
$st->execute([$id, $u['id']]);
$n = $st->fetch();
if (!$n) ir('dashboard_reporter.php');

$erro = $ok = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $titulo  = trim($_POST['titulo']  ?? '');
    $noticia = trim($_POST['noticia'] ?? '');
    $imagem  = $n['imagem'];

    if (!$titulo || !$noticia) { $erro = 'Título e conteúdo são obrigatórios.'; }
    else {
        if (!empty($_FILES['imagem']['name'])) {
            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext,['jpg','jpeg','png','webp','gif'])) { $erro='Formato não permitido.'; }
            elseif ($_FILES['imagem']['size']>3*1024*1024) { $erro='Imagem máx. 3 MB.'; }
            else {
                if ($imagem && file_exists('imagens/'.$imagem)) unlink('imagens/'.$imagem);
                $imagem = uniqid('img_',true).'.'.$ext;
                move_uploaded_file($_FILES['imagem']['tmp_name'], 'imagens/'.$imagem);
            }
        }
        if (!$erro) {
            $pdo->prepare("UPDATE noticias SET titulo=?,noticia=?,imagem=? WHERE id=? AND autor=?")
                ->execute([$titulo, $noticia, $imagem, $id, $u['id']]);
            $ok = 'Publicação atualizada!';
            $n  = array_merge($n, ['titulo'=>$titulo,'noticia'=>$noticia,'imagem'=>$imagem]);
        }
    }
}

$pageTitle = 'Editar Publicação';
$backLink  = ['dashboard_reporter.php', 'Painel'];
include '_header.php';
?>

<div class="page-form">
    <div class="form-card wide">
        <div class="form-top">
            <span class="form-brand">Ver<em>nissage</em></span>
            <h2>Editar Publicação</h2>
        </div>
        <?php if ($erro): ?><div class="alert alert-err"><?= limpar($erro) ?></div><?php endif; ?>
        <?php if ($ok):   ?><div class="alert alert-ok"><?= limpar($ok) ?></div><?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="field">
                <label>Título</label>
                <input type="text" name="titulo" value="<?= limpar($n['titulo']) ?>" required>
            </div>
            <div class="field">
                <label>Conteúdo</label>
                <textarea name="noticia" rows="12" required><?= limpar($n['noticia']) ?></textarea>
            </div>
            <div class="field">
                <label>Nova imagem (substitui a atual)</label>
                <?php if ($n['imagem'] && file_exists('imagens/'.$n['imagem'])): ?>
                    <img src="imagens/<?= limpar($n['imagem']) ?>"
                         style="max-height:130px;margin-bottom:.5rem;border-radius:var(--r);border:var(--border-line)">
                <?php endif; ?>
                <input type="file" name="imagem" accept="image/*">
            </div>
            <div style="display:flex;gap:.7rem;flex-wrap:wrap">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="dashboard_reporter.php" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include '_footer.php'; ?>
