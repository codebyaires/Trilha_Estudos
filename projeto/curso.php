<?php
session_start();
require_once "includes/conexao.php";
require_once "includes/logado.php";

$usuario_id = $_SESSION["usuario_id"];

// 1. Pega o ID do curso que o aluno clicou (vem da URL: curso.php?id=3)
$curso_id = $_GET["id"] ?? 0;

// 2. SEGURANÇA: Verifica se o aluno logado realmente está matriculado neste curso específico
$sql_matricula = "SELECT id FROM inscricoes WHERE usuario_id = '$usuario_id' AND curso_id = '$curso_id'";
$res_matricula = mysqli_query($conexao, $sql_matricula);

// Se não achar a matrícula, chuta ele de volta pra mochila
if (mysqli_num_rows($res_matricula) === 0) {
    header("Location: meus_cursos.php");
    exit;
}

// 3. Busca os dados APENAS DESTE CURSO (para colocar o título e a capa lá no cabeçalho)
$sql_curso = "SELECT * FROM cursos WHERE id = '$curso_id'";
$curso = mysqli_fetch_assoc(mysqli_query($conexao, $sql_curso));

// 4. CONTAGEM GERAL: Quantos módulos esse curso tem no total?
$sql_tot_modulos = "SELECT COUNT(id) AS total FROM modulos WHERE curso_id = '$curso_id'";
$tot_modulos = mysqli_fetch_assoc(mysqli_query($conexao, $sql_tot_modulos))['total'];

// 5. CONTAGEM GERAL: Quantas aulas esse curso tem no total? (Cruza a tabela aulas com a tabela modulos)
$sql_tot_aulas = "SELECT COUNT(aulas.id) AS total FROM aulas 
                  INNER JOIN modulos ON aulas.modulo_id = modulos.id 
                  WHERE modulos.curso_id = '$curso_id'";
$tot_aulas = mysqli_fetch_assoc(mysqli_query($conexao, $sql_tot_aulas))['total'];

// 6. Busca a lista de MÓDULOS para usarmos no laço 'while' lá no HTML
$sql_modulos = "SELECT * FROM modulos WHERE curso_id = '$curso_id' ORDER BY ordem ASC";
$resultado_modulos = mysqli_query($conexao, $sql_modulos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ['titulo']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { senai: { red:'#C0392B', blue:'#34679A', 'blue-dark':'#2C5A85', orange:'#E67E22', green:'#27AE60' } } } }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .modulo-header { cursor: pointer; }
        .modulo-body { display: block; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
 
    <!-- NAVBAR -->
    <nav class="bg-senai-blue shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-3 flex items-center gap-6">
            <a href="index.php" class="flex items-center gap-2 text-white font-extrabold text-lg">🎓 EAD SENAI</a>
            <a href="cursos.php"      class="text-blue-200 hover:text-white text-sm transition">Cursos</a>
            <a href="meus_cursos.php" class="text-blue-200 hover:text-white text-sm transition">Meus Cursos</a>
            <div class="flex-1"></div>
            <span class="text-sm text-blue-200">Olá, <strong class="text-white"><?php echo $_SESSION['usuario_nome'] ?? 'Aluno'; ?></strong></span>
            <a href="login.php" class="bg-senai-red text-white text-xs font-semibold px-3 py-1.5 rounded hover:bg-red-700 transition">Sair</a>
        </div>
    </nav>

    <div class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-6xl mx-auto">
            
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                <a href="meus_cursos.php" class="hover:text-senai-blue transition">Meus Cursos</a>
                <span></span>
                <span class="text-gray-700 font-semibold"><?php echo $curso['titulo']; ?></span>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 w-14 h-14 rounded-lg flex items-center justify-center shadow-sm">
                    <span class="text-3xl">🌐</span>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-gray-800"><?php echo $curso['titulo']; ?></h1>
                    
                    <div class="flex gap-4 text-xs text-gray-500 mt-1">
                        <span>📚 <?php echo $tot_modulos; ?> Módulos</span>
                        <span>🎬 <?php echo $tot_aulas; ?> Aulas Disponíveis</span>
                        
                        <span class="text-senai-green font-semibold">✓ 0 aulas concluídas</span>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <main class="max-w-6xl mx-auto px-6 py-8">
        
        <?php while ($modulo = mysqli_fetch_assoc($resultado_modulos)): ?>
            
            <?php
            $id_deste_modulo = $modulo['id'];
            $sql_qtd_aulas_modulo = "SELECT COUNT(id) AS total FROM aulas WHERE modulo_id = '$id_deste_modulo'";
            $qtd_aulas_modulo = mysqli_fetch_assoc(mysqli_query($conexao, $sql_qtd_aulas_modulo))['total'];
            ?>

            <div class="mb-4 bg-gray-50 p-4 border rounded-lg">
                <h2 class="font-bold"><?php echo $modulo['titulo']; ?></h2>
                <p class="text-sm text-gray-500">Este módulo tem <?php echo $qtd_aulas_modulo; ?> aulas.</p>
                </div>

        <?php endwhile; ?>
        
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-400 text-center py-4 text-xs">
        SENAI — Sistema EAD &nbsp;|&nbsp; © 2025 Todos os direitos reservados
    </footer>

</body>
</html>
