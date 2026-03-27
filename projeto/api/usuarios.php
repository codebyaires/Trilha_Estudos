<?php
// api/usuarios.php

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
            // Buscar UM usuario — ex: api/usuarios.php?id=2
            $sql = "SELECT * FROM usuarios WHERE id = $id";
            $resultado = mysqli_query($conexao, $sql);
            $usuario = mysqli_fetch_assoc($resultado);
    
            if ($usuario) {
                http_response_code(200);
                echo json_encode($usuario);
            } else {
                http_response_code(404);
                echo json_encode(["erro" => "Usuario não encontrado"]);
            }
        } else {
            // Listar TODOS os usuarios ativos — ex: api/usuarios.php
            $sql = "SELECT * FROM usuarios ORDER BY nome";
            $resultado = mysqli_query($conexao, $sql);
            $usuarios = [];
            while ($row = mysqli_fetch_assoc($resultado)) {
                $usuarios[] = $row;
            }
            http_response_code(200);
            echo json_encode($usuarios);
        }
        break;
    case 'POST':
        // Ler o JSON enviado pelo cliente (body da requisição)
        $dados = json_decode(file_get_contents("php://input"), true);

       
        // Validar campos obrigatórios da tabela usuarios
        if (!isset($dados['nome']) || !isset($dados['email'])) {
            http_response_code(400);
            echo json_encode(["erro" => "Nome e email são obrigatórios"]);
            break;
        }
    
        // Escapar para evitar SQL Injection
        $nome = mysqli_real_escape_string($conexao, $dados['nome']);
        $email = mysqli_real_escape_string($conexao, $dados['email']);
    
        $sql = "INSERT INTO usuarios (nome, email)
                VALUES ('$nome', '$email')";
        mysqli_query($conexao, $sql);
        $novo_id = mysqli_insert_id($conexao);
    
        http_response_code(201);
        echo json_encode(["mensagem" => "Usuario criado", "id" => $novo_id]);
        break;
    case 'PUT':
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(["erro" => "Informe o ID: ?id=1"]);
            break;
        }
    
        $dados = json_decode(file_get_contents("php://input"), true);
    
        $nome = mysqli_real_escape_string($conexao, $dados['nome']);
        $email = mysqli_real_escape_string($conexao, $dados['email']);
    
        $sql = "UPDATE usuarios SET
                nome = '$nome',
                email = '$email'
                WHERE id = $id";
    
        mysqli_query($conexao, $sql);
    
        if (mysqli_affected_rows($conexao) > 0) {
            http_response_code(200);
            echo json_encode(["mensagem" => "Usuario atualizado"]);
        } else {
            http_response_code(404);
            echo json_encode(["erro" => "Usuario não encontrado"]);
        }
        break;
    case 'DELETE':
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(["erro" => "Informe o ID na URL"]);
            break;
        }
    
        $sql = "DELETE FROM usuarios WHERE id = $id";
        mysqli_query($conexao, $sql);
    
        if (mysqli_affected_rows($conexao) > 0) {
            http_response_code(200);
            echo json_encode(["mensagem" => "Usuario removido"]);
        } else {
            http_response_code(404);
            echo json_encode(["erro" => "Usuario não encontrado"]);
        }
        break;
}