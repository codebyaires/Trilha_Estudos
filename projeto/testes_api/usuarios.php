<?php
// consumir_cursos.php — Buscar todos os cursos via API

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://localhost:8080/trilha_estudos/projeto/api/usuarios.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$resposta = curl_exec($ch);
curl_close($ch);

$usuarios = json_decode($resposta, true);

// echo '<pre>';
// print_r($cursos);
// die;

// Exibir os cursos na tela
foreach ($usuarios as $usuario) {
    echo "<div class='bg-white rounded-lg shadow p-4'>";
    echo "  <h3>" . $usuario['nome'] . "</h3>";
    echo "  <p>" . $usuario['email'] . "</p>";
    echo "  <a href='usuarios.php?id=" . $usuario['id'] . "'>Acessar</a>";
    echo "</div>";
}