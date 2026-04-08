<?php
function limpar(string $s): string {
    return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
}
function ir(string $url): void { header("Location: $url"); exit; }
function logado(): bool { return !empty($_SESSION['uid']); }
function ehAdmin(): bool { return ($_SESSION['utipo'] ?? '') === 'admin'; }
function sessaoUser(): array {
    return ['id'=>$_SESSION['uid']??null,'nome'=>$_SESSION['unome']??'','tipo'=>$_SESSION['utipo']??'reporter'];
}
function iniciarSessao(array $u): void {
    $_SESSION['uid']   = $u['id'];
    $_SESSION['unome'] = $u['nome'];
    $_SESSION['utipo'] = $u['tipo'];
}
function encerrarSessao(): void { session_unset(); session_destroy(); }
function dataFmt(string $d): string { return (new DateTime($d))->format('d/m/Y H:i'); }
function resumo(string $t, int $n=180): string {
    $t = strip_tags($t);
    return mb_strlen($t)<=$n ? $t : mb_substr($t,0,$n).'…';
}
