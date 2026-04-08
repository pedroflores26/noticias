// theme.js — Modo claro / escuro
(function () {
    const STORAGE_KEY = 'vernissage_theme';

    function aplicarTema(tema) {
        document.documentElement.setAttribute('data-theme', tema);
        localStorage.setItem(STORAGE_KEY, tema);
        const btn = document.getElementById('theme-toggle');
        if (btn) btn.textContent = tema === 'dark' ? '☀️' : '🌙';
    }

    // Aplica tema salvo (ou padrão claro) antes do render
    const salvo = localStorage.getItem(STORAGE_KEY) || 'light';
    aplicarTema(salvo);

    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('theme-toggle');
        if (btn) {
            btn.textContent = salvo === 'dark' ? '☀️' : '🌙';
            btn.addEventListener('click', function () {
                const atual = document.documentElement.getAttribute('data-theme');
                aplicarTema(atual === 'dark' ? 'light' : 'dark');
            });
        }
    });
})();
