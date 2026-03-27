<?php
// criar_curso.php — Envia dados para a API criar um curso

$dados = json_encode([
    "nome"    => "JavaScript Moderno 2",
    "email" => "ES6+, async/await, manipulação do DOM"
]);

$ch = curl_init("http://localhost:8080/trilha_estudos/projeto/api/usuarios.php");

curl_setopt($ch, CURLOPT_POST, true);                    // Verbo POST
curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);             // Body com JSON
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"                      // Avisa: estou enviando JSON
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$resposta = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    // 201 = criado!
curl_close($ch);

$resultado = json_decode($resposta, true);
echo "Status: $http_code | ID: " . $resultado['id'];