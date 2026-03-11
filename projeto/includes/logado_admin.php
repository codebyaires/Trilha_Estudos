<?php
// Garante que a sessão está rodando
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// A TRAVA DUPLA DE SEGURANÇA:
// 1. Se não tiver ID (não fez login) 
// OU (||) 
// 2. Se o tipo de usuário for diferente de 'admin'
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] !== 'admin') {
    
    // Expulsa o invasor de volta para a tela de login
    header("Location: ../login.php");
    exit;
}
?>