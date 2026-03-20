<?php
// Garante que a sessão está rodando
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_logado = $_SESSION['usuario_id']; 

$sql_usuario = "SELECT email FROM usuarios WHERE id = '$id_logado'";
$res_usuario = mysqli_query($conexao, $sql_usuario);

// Se o usuário for encontrado, salva os dados na variável $dados_usuario
if ($res_usuario && mysqli_num_rows($res_usuario) > 0) {
    $dados_usuario = mysqli_fetch_assoc($res_usuario);
} else {
    // Valor padrão caso dê algum erro na busca
    $dados_usuario['email'] = "Email não encontrado"; 
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