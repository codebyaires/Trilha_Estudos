<?php
session_start();
require_once "includes/conexao.php";
require_once "includes/logado.php"; // Garante que só alunos logados acessem

// Pega quem é o aluno e qual curso ele quer
$usuario_id = $_SESSION["usuario_id"];
$curso_id = $_GET["curso_id"] ?? null; // Vem da URL: inscricao.php?curso_id=X

// Só avança se tiver um ID de curso na URL
if ($curso_id) {
    
    // SEGURANÇA: Verifica se o aluno já não está inscrito (evita duplicidade se ele recarregar a página)
    $sql_verifica = "SELECT id FROM inscricoes WHERE usuario_id = ? AND curso_id = ?";
    $stmt_verifica = $conexao->prepare($sql_verifica);
    $stmt_verifica->bind_param("ii", $usuario_id, $curso_id);
    $stmt_verifica->execute();
    $resultado = $stmt_verifica->get_result();

    // Se o resultado for 0 (zero), significa que ele ainda não tem o curso
    if ($resultado->num_rows === 0) {
        
        // Faz a mágica acontecer: Insere na tabela 'inscricoes'
        $sql_insere = "INSERT INTO inscricoes (usuario_id, curso_id) VALUES (?, ?)";
        $stmt_insere = $conexao->prepare($sql_insere);
        $stmt_insere->bind_param("ii", $usuario_id, $curso_id);
        $stmt_insere->execute();     
    }
}

// Redireciona de volta para o catálogo ativando a barrinha verde de sucesso
header("Location: cursos.php?sucesso=1");
exit;
?>