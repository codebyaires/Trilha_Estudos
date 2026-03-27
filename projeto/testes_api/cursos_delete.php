<?php
// excluir_curso.php — Remove o curso ID 5 via API

$id = 5;

$ch = curl_init("http://localhost:8080/trilha_estudos/projeto/api/cursos.php?id=$id");

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");     // Verbo DELETE
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$resposta = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$resultado = json_decode($resposta, true);

if ($http_code === 200) {
    echo $resultado['mensagem'];  // "Curso removido"
} else {
    echo "Erro: " . $resultado['erro'];  // "Curso não encontrado"
}