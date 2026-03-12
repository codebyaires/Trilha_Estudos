<?php
// 1. Inicia a sessão para o PHP saber qual crachá ele vai destruir
session_start();

// 2. Limpa todas as informações que estavam guardadas (ID, nome, tipo)
$_SESSION = array();

// 3. Destrói a sessão de fato
session_destroy();

// 4. Manda o usuário de volta para a tela inicial
header("Location: index.php");
exit;
?>