<?php
// consumir_cursos.php — Buscar todos os cursos via API

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://localhost:8080/trilha_estudos/projeto/api/cursos.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$resposta = curl_exec($ch);
curl_close($ch);

$cursos = json_decode($resposta, true);

// echo '<pre>';
// print_r($cursos);
// die;

// Exibir os cursos na tela
foreach ($cursos as $curso) {
    echo "<div class='bg-white rounded-lg shadow p-4'>";
    echo "  <h3>" . $curso['titulo'] . "</h3>";
    echo "  <p>" . $curso['descricao'] . "</p>";
    echo "  <a href='../curso.php?id=" . $curso['id'] . "'>Acessar</a>";
    echo "</div>";
}