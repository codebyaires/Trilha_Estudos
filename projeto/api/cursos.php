<?php
// api/cursos.php

// Headers para responder JSON e permitir acesso externo
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Preflight do CORS (navegador envia antes de POST/PUT/DELETE)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

// Reutilizar a conexão do projeto
require_once "../includes/conexao.php";

// Capturar verbo HTTP e ID da query string
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Roteamento pelo verbo
switch ($method) {
    case 'GET':
        if ($id > 0) {
            // Buscar UM curso — ex: api/cursos.php?id=2
            $sql = "SELECT * FROM cursos WHERE id = $id";
            $resultado = mysqli_query($conexao, $sql);
            $curso = mysqli_fetch_assoc($resultado);
    
            if ($curso) {
                http_response_code(200);
                echo json_encode($curso);
            } else {
                http_response_code(404);
                echo json_encode(["erro" => "Curso não encontrado"]);
            }
        } else {
            // Listar TODOS os cursos ativos — ex: api/cursos.php
            $sql = "SELECT * FROM cursos WHERE ativo = 1 ORDER BY titulo";
            $resultado = mysqli_query($conexao, $sql);
            $cursos = [];
            while ($row = mysqli_fetch_assoc($resultado)) {
                $cursos[] = $row;
            }
            http_response_code(200);
            echo json_encode($cursos);
        }
        break;
    case 'POST':
        // Ler o JSON enviado pelo cliente (body da requisição)
        $dados = json_decode(file_get_contents("php://input"), true);

       
        // Validar campos obrigatórios da tabela cursos
        if (!isset($dados['titulo']) || !isset($dados['descricao'])) {
            http_response_code(400);
            echo json_encode(["erro" => "Título e descrição são obrigatórios"]);
            break;
        }
    
        // Escapar para evitar SQL Injection
        $titulo = mysqli_real_escape_string($conexao, $dados['titulo']);
        $descricao = mysqli_real_escape_string($conexao, $dados['descricao']);
    
        $sql = "INSERT INTO cursos (titulo, descricao)
                VALUES ('$titulo', '$descricao')";
        mysqli_query($conexao, $sql);
        $novo_id = mysqli_insert_id($conexao);
    
        http_response_code(201);
        echo json_encode(["mensagem" => "Curso criado", "id" => $novo_id]);
        break;
    case 'PUT':
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(["erro" => "Informe o ID: ?id=1"]);
            break;
        }
    
        $dados = json_decode(file_get_contents("php://input"), true);
    
        $titulo = mysqli_real_escape_string($conexao, $dados['titulo']);
        $descricao = mysqli_real_escape_string($conexao, $dados['descricao']);
    
        $sql = "UPDATE cursos SET
                titulo = '$titulo',
                descricao = '$descricao'
                WHERE id = $id";
    
        mysqli_query($conexao, $sql);
    
        if (mysqli_affected_rows($conexao) > 0) {
            http_response_code(200);
            echo json_encode(["mensagem" => "Curso atualizado"]);
        } else {
            http_response_code(404);
            echo json_encode(["erro" => "Curso não encontrado"]);
        }
        break;
    case 'DELETE':
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(["erro" => "Informe o ID na URL"]);
            break;
        }
    
        $sql = "DELETE FROM cursos WHERE id = $id";
        mysqli_query($conexao, $sql);
    
        if (mysqli_affected_rows($conexao) > 0) {
            http_response_code(200);
            echo json_encode(["mensagem" => "Curso removido"]);
        } else {
            http_response_code(404);
            echo json_encode(["erro" => "Curso não encontrado"]);
        }
        break;
}