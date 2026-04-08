<?php
// 🔒 Inicia sessão com segurança
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'funcoes.php';
require_once 'conexao.php';

// 🔒 Protege a página (só logado entra)
if (!logado()) {
    ir('login.php');
}

$u    = sessaoUser();
$erro = '';
$painel = ehAdmin() ? 'dashboard_admin.php' : 'dashboard_reporter.php';

// 📩 Processa envio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo  = trim($_POST['titulo']  ?? '');
    $noticia = trim($_POST['noticia'] ?? '');
    $imagem  = null;

    if (!$titulo || !$noticia) {
        $erro = 'Título e conteúdo são obrigatórios.';
    } else {

        // 🖼 Upload de imagem
        if (!empty($_FILES['imagem']['name'])) {
            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
                $erro = 'Formato não permitido (use JPG, PNG, WEBP, GIF).';
            } elseif ($_FILES['imagem']['size'] > 3 * 1024 * 1024) {
                $erro = 'Imagem deve ter no máximo 3 MB.';
            } else {
                // cria nome único
                $imagem = uniqid('img_', true) . '.' . $ext;

                // cria pasta se não existir
                if (!is_dir('imagens')) {
                    mkdir('imagens', 0777, true);
                }

                move_uploaded_file($_FILES['imagem']['tmp_name'], 'imagens/' . $imagem);
            }
        }

        // 💾 Salva no banco
        if (!$erro) {
            $stmt = $pdo->prepare("INSERT INTO noticias (titulo, noticia, autor, imagem) VALUES (?, ?, ?, ?)");
            $stmt->execute([$titulo, $noticia, $u['id'], $imagem]);

            ir($painel);
        }
    }
}

$pageTitle = 'Nova Publicação';
$backLink  = [$painel, 'Painel'];

include '_header.php';
?>

<div class="page-form">
    <div class="form-card wide">
        <div class="form-top">
            <span class="form-brand">Ver<em>nissage</em></span>
            <h2>Nova Publicação</h2>
            <p>Compartilhe com a comunidade cultural</p>
        </div>

        <?php if ($erro): ?>
            <div class="alert alert-err"><?= limpar($erro) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="field">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo"
                       value="<?= limpar($_POST['titulo'] ?? '') ?>"
                       placeholder="Título da matéria" required>
            </div>

            <div class="field">
                <label for="noticia">Conteúdo</label>
                <textarea id="noticia" name="noticia" rows="12"
                          placeholder="Escreva sua matéria..." required><?= limpar($_POST['noticia'] ?? '') ?></textarea>
            </div>

            <div class="field">
                <label for="imagem">Imagem de capa (opcional, máx. 3 MB)</label>
                <input type="file" id="imagem" name="imagem" accept="image/*">
            </div>

            <div style="display:flex;gap:.7rem;flex-wrap:wrap;margin-top:.5rem">
                <button type="submit" class="btn btn-accent">Publicar</button>
                <a href="<?= $painel ?>" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include '_footer.php'; ?>