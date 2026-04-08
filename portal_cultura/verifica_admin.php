<?php
if (session_status()===PHP_SESSION_NONE) session_start();
require_once __DIR__.'/funcoes.php';
if (!logado()) ir('login.php');
if (!ehAdmin()) ir('dashboard_reporter.php');
