<?php
session_start();
require_once "includes/conexao.php";
require_once "includes/logado.php";

$usuario_id = $_SESSION["usuario_id"];

// Busca TODOS os cursos cadastrados (Trazendo os mais novos primeiro)
$sql_cursos = "SELECT * FROM cursos ORDER BY id DESC";
$resultado_cursos = mysqli_query($conexao, $sql_cursos);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Cursos — EAD SENAI</title>
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
            
            <a href="index.php" class="flex items-center gap-2 text-white font-extrabold text-xl tracking-wide leading-none">
                🎓 EAD SENAI
            </a>
            
            <a href="cursos.php" class="text-white text-base font-bold border-b-2 border-white pb-0.5 leading-none mt-1">Cursos</a>
            <a href="meus_cursos.php" class="text-blue-200 hover:text-white text-base font-medium transition-colors leading-none mt-1">Meus Cursos</a>
            
            <div class="flex-1"></div>
            
            <div class="flex items-center gap-5">
                <span class="text-base text-blue-200 leading-none mt-0.5">Olá, <strong class="text-white"><?php echo $_SESSION['usuario_nome'] ?? 'Aluno'; ?></strong></span>
                
                <a href="logout.php" class="bg-senai-red hover:bg-red-700 text-white text-sm font-bold px-6 py-1.5 rounded-md transition-colors shadow-sm">
                    Sair
                </a>
            </div>

        </div>
    </nav>

    <div class="bg-white border-b border-gray-200 px-6 py-5">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-2xl font-extrabold text-gray-800">Catálogo de Cursos</h1>
            <p class="text-sm text-gray-500 mt-1">Escolha um curso, inscreva-se e comece a aprender agora mesmo.</p>
        </div>
    </div>

    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
    <div class="max-w-6xl mx-auto px-6 pt-5">
        <div class="bg-green-50 border border-green-300 text-green-700 rounded-lg p-3 flex items-center gap-2 text-sm">
            <span class="font-bold text-lg">✓</span>
            <span>Inscrição realizada com sucesso! Acesse <a href="meus_cursos.php" class="underline font-semibold">Meus Cursos</a> para começar.</span>
        </div>
    </div>
    <?php endif; ?>

<main class="max-w-6xl mx-auto px-6 py-10 flex-1">  
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while ($curso = mysqli_fetch_assoc($resultado_cursos)): ?>
                
                <?php
                // Pega o ID deste curso específico
                $curso_id_atual = $curso['id'];
                
                // Vai no banco e pergunta se o Aluno atual já se matriculou
                $sql_verifica = "SELECT id FROM inscricoes WHERE usuario_id = '$usuario_id' AND curso_id = '$curso_id_atual'";
                $resultado_verifica = mysqli_query($conexao, $sql_verifica);
                
                $ja_inscrito = mysqli_num_rows($resultado_verifica) > 0;
                ?>

                <div class="bg-white rounded-2xl shadow hover:shadow-lg transition-all overflow-hidden flex flex-col border border-gray-100">
                    
                    <div class="bg-gradient-to-br from-senai-blue to-blue-dark h-52 flex items-center justify-center overflow-hidden">
                        <?php if(!empty($curso['capa'])): ?>
                            <img src="uploads/<?php echo $curso['capa']; ?>" alt="Capa" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-white opacity-50 text-sm font-semibold tracking-wider uppercase">Sem Capa</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="font-bold text-gray-800 text-xl mb-3"><?php echo $curso['titulo']; ?></h3>
                        
                        <p class="text-gray-600 mb-8 flex-1 line-clamp-3 leading-relaxed">
                            <?php echo $curso['descricao']; ?>
                        </p>
                        
                        <?php if ($ja_inscrito): ?>
                            
                            <a href="curso.php?id=<?php echo $curso_id_atual; ?>" class="bg-green-100 text-green-700 hover:bg-green-200 text-base font-bold py-3.5 rounded-xl text-center transition-colors shadow-sm">
                                Já Inscrito — Acessar
                            </a>
                            
                        <?php else: ?>
                            
                            <a href="inscricao.php?curso_id=<?php echo $curso_id_atual; ?>" class="bg-senai-blue hover:bg-senai-blue-dark text-white text-base font-bold py-3.5 rounded-xl text-center transition-colors shadow-md hover:shadow-lg">
                                Inscrever-se Grátis
                            </a>
                            
                        <?php endif; ?>
                    </div>
                </div>

            <?php endwhile; ?>
            
        </div>
    </main>

    <footer class="bg-gray-900 text-gray-400 text-center py-4 text-xs mt-auto">
        SENAI — Sistema EAD &nbsp;|&nbsp; © 2026 Todos os direitos reservados
    </footer>

</body>
</html>