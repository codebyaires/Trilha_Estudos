<?php
session_start();

require_once "includes/conexao.php";
require_once "includes/logado.php";

$usuario_id = $_SESSION["usuario_id"];

// Busca apenas os cursos que o aluno está inscrito usando INNER JOIN
$sql_cursos = "SELECT cursos.* FROM cursos 
               INNER JOIN inscricoes ON cursos.id = inscricoes.curso_id 
               WHERE inscricoes.usuario_id = '$usuario_id' 
               ORDER BY cursos.id DESC";

$resultado_cursos = mysqli_query($conexao, $sql_cursos);
$total_cursos = mysqli_num_rows($resultado_cursos); // Conta quantos cursos ele tem
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Cursos — EAD SENAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { senai: { red:'#C0392B', blue:'#34679A', 'blue-dark':'#2C5A85', orange:'#E67E22', green:'#27AE60' } } } }
        }
    </script> 
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <nav class="bg-senai-blue shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-3 flex items-center gap-8">
            <a href="index.php" class="flex items-center gap-2 text-white font-extrabold text-xl leading-none">🎓 EAD SENAI</a>
            <a href="cursos.php"      class="text-blue-200 hover:text-white text-base font-medium transition-colors mt-1">Cursos</a>
            <a href="meus_cursos.php" class="text-white text-base font-bold border-b-2 border-white pb-0.5 mt-1">Meus Cursos</a>
            <div class="flex-1"></div>
            <div class="flex items-center gap-5">
                <span class="text-base text-blue-200 mt-0.5">Olá, <strong class="text-white"><?php echo $_SESSION['usuario_nome'] ?? 'Aluno'; ?></strong></span>
                <a href="logout.php" class="bg-senai-red hover:bg-red-700 text-white text-sm font-bold px-6 py-1.5 rounded-md transition-colors shadow-sm">Sair</a>
            </div>
        </div>
    </nav>

    <div class="bg-white border-b border-gray-200 px-6 py-5">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800">Meus Cursos</h1>
                <p class="text-sm text-gray-500 mt-1">Bem-vindo(a) de volta! Continue de onde parou.</p>
            </div>
            <a href="cursos.php" class="border-2 border-senai-blue text-senai-blue text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                + Explorar mais cursos
            </a>
        </div>
    </div>

    <main class="max-w-6xl mx-auto px-6 py-8 flex-1 w-full">

        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center shadow-sm">
                <p class="text-2xl font-extrabold text-senai-blue"><?php echo $total_cursos; ?></p>
                <p class="text-xs text-gray-500 mt-1">Cursos inscritos</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center shadow-sm">
                <p class="text-2xl font-extrabold text-senai-green">0</p>
                <p class="text-xs text-gray-500 mt-1">Aulas concluídas (Bônus)</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center shadow-sm">
                <p class="text-2xl font-extrabold text-senai-orange">0%</p>
                <p class="text-xs text-gray-500 mt-1">Progresso geral</p>
            </div>
        </div>

        <h2 class="font-bold text-gray-700 mb-4">Cursos em Andamento</h2>
        <div class="space-y-4">

            <?php if ($total_cursos > 0): ?>
                
                <?php while ($curso = mysqli_fetch_assoc($resultado_cursos)): ?>
                    
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 flex gap-5 p-5 items-center">
                        
                        <div class="bg-gradient-to-br from-senai-blue to-blue-dark w-24 h-20 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <?php if(!empty($curso['capa'])): ?>
                                <img src="uploads/<?php echo $curso['capa']; ?>" alt="Capa" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-white opacity-50 text-xs uppercase tracking-wider">S/ Capa</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base mt-1"><?php echo $curso['titulo']; ?></h3>
                                    <p class="text-xs text-gray-400 mt-0.5">Inscrição ativa</p>
                                </div>
                                
                                <a href="curso.php?id=<?php echo $curso['id']; ?>" class="bg-senai-blue text-white text-xs font-bold px-5 py-2.5 rounded-lg hover:bg-senai-blue-dark transition flex-shrink-0 shadow-sm">
                                    Continuar →
                                </a>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>

            <?php else: ?>
                <div class="bg-white rounded-xl border border-dashed border-gray-300 p-10 text-center">
                    <span class="text-4xl">🎒</span>
                    <h3 class="text-gray-700 font-bold text-lg mt-3">Sua mochila está vazia!</h3>
                    <p class="text-gray-500 text-sm mt-1">Você ainda não se inscreveu em nenhum curso.</p>
                    <a href="cursos.php" class="inline-block mt-4 bg-senai-blue text-white text-sm font-bold px-5 py-2 rounded-lg hover:bg-senai-blue-dark transition">
                        Ver Catálogo
                    </a>
                </div>
            <?php endif; ?>

        </div>

        <?php if ($total_cursos > 0): ?>
            <div class="mt-10 bg-senai-blue rounded-2xl p-6 text-white text-center shadow-md">
                <h3 class="font-extrabold text-lg mb-1">Quer aprender mais?</h3>
                <p class="text-blue-200 text-sm mb-4">Temos outros cursos disponíveis no catálogo.</p>
                <a href="cursos.php" class="inline-block bg-white text-senai-blue font-bold px-6 py-2.5 rounded-lg text-sm hover:bg-blue-50 transition">
                    Ver todos os cursos
                </a>
            </div>
        <?php endif; ?>

    </main>

    <footer class="bg-gray-900 text-gray-400 text-center py-4 text-xs mt-auto">
        SENAI — Sistema EAD &nbsp;|&nbsp; © 2026 Todos os direitos reservados
    </footer>

</body>
</html>