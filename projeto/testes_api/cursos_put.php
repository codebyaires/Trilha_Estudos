<?php
// atualizar_curso.php — Atualiza o curso ID 2 via API

$id = 2;
$dados = json_encode([
    "titulo"    => "PHP do Zero ao Avançado",
    "descricao" => "Curso atualizado com PDO e API REST"
]);

$ch = curl_init("http://localhost:8080/trilha_estudos/projeto/api/cursos.php?id=$id");

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