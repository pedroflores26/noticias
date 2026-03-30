<?php
// logout.php
session_start();
require_once 'funcoes.php';
deslogarUsuario();
redirecionar('index.php');
