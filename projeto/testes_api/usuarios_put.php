<?php
// atualizar_curso.php — Atualiza o curso ID 2 via API

$id = 5;
$dados = json_encode([
    "nome"    => "João",
    "email" => "joao@email.com"
]);

$ch = curl_init("http://localhost:8080/trilha_estudos/projeto/api/usuarios.php?id=$id");

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");          // Verbo PUT
curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$resposta = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$resultado = json_decode($resposta, true);
echo $resultado['mensagem'];  // "Curso atualizado"