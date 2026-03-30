<?php
// funcoes.php – Funções auxiliares do sistema

function limpar(string $str): string {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

function redirecionar(string $url): void {
    header("Location: $url");
    exit;
}

function estaLogado(): bool {
    return isset($_SESSION['usuario_id']);
}

function usuarioLogado(): array {
    return [
        'id'   => $_SESSION['usuario_id']   ?? null,
        'nome' => $_SESSION['usuario_nome'] ?? '',
    ];
}

function logarUsuario(array $usuario): void {
    $_SESSION['usuario_id']   = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
}

function deslogarUsuario(): void {
    session_unset();
    session_destroy();
}

function formatarData(string $data): string {
    $dt = new DateTime($data);
    setlocale(LC_TIME, 'pt_BR.UTF-8', 'Portuguese_Brazil');
    return $dt->format('d/m/Y \à\s H:i');
}

function resumo(string $texto, int $chars = 180): string {
    $texto = strip_tags($texto);
    if (mb_strlen($texto) <= $chars) return $texto;
    return mb_substr($texto, 0, $chars) . '…';
}
