<?php
session_start();

require_once "includes/conexao.php";
require_once "includes/logado.php";

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

    <!-- NAVBAR -->
    <nav class="bg-senai-blue shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-3 flex items-center gap-6">
            <a href="index.php" class="flex items-center gap-2 text-white font-extrabold text-lg">🎓 EAD SENAI</a>
            <a href="cursos.php"      class="text-blue-200 hover:text-white text-sm transition">Cursos</a>
            <a href="meus_cursos.php" class="text-white text-sm font-semibold border-b-2 border-white pb-0.5">Meus Cursos</a>
            <div class="flex-1"></div>
            <strong class="text-white">Olá, <?php echo $_SESSION['usuario_nome'] ?? 'Aluno'; ?></strong>
            <a href="login.php" class="bg-senai-red text-white text-xs font-semibold px-3 py-1.5 rounded hover:bg-red-700 transition">Sair</a>
        </div>
    </nav>

    <!-- CABEÇALHO -->
    <div class="bg-white border-b border-gray-200 px-6 py-5">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800">Meus Cursos</h1>
                <p class="text-sm text-gray-500 mt-1">Bem-vindo(a) de volta, <strong class="text-black"><?php echo $_SESSION['usuario_nome'] ?? 'Aluno'; ?></strong>! Continue de onde parou.</p>
            </div>
            <a href="cursos.php" class="border-2 border-senai-blue text-senai-blue text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                + Explorar mais cursos
            </a>
        </div>
    </div>

    <main class="max-w-6xl mx-auto px-6 py-8 flex-1 w-full">

        <!-- RESUMO DO ALUNO -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center shadow-sm">
                <p class="text-2xl font-extrabold text-senai-blue">2</p>
                <p class="text-xs text-gray-500 mt-1">Cursos inscritos</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center shadow-sm">
                <p class="text-2xl font-extrabold text-senai-green">3</p>
                <p class="text-xs text-gray-500 mt-1">Aulas concluídas</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center shadow-sm">
                <p class="text-2xl font-extrabold text-senai-orange">21%</p>
                <p class="text-xs text-gray-500 mt-1">Progresso geral</p>
            </div>
        </div>

        <!-- LISTA DE CURSOS INSCRITOS -->
        <h2 class="font-bold text-gray-700 mb-4">Cursos em Andamento</h2>
        <div class="space-y-4">

            <!-- Curso 1 — em andamento -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 flex gap-5 p-5 items-center">
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 w-24 h-20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="text-4xl">🌐</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded">Front-end</span>
                            <h3 class="font-bold text-gray-800 text-base mt-1">HTML e CSS do Zero</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Inscrito em 01/03/2025</p>
                        </div>
                        <a href="curso.php" class="bg-senai-blue text-white text-xs font-bold px-5 py-2.5 rounded-lg hover:bg-senai-blue-dark transition flex-shrink-0">
                            Continuar →
                        </a>
                    </div>
                    <!-- Barra de progresso -->
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                            <span>Progresso: Módulo 1 — Tags essenciais</span>
                            <span class="text-senai-green font-semibold">3 / 9 aulas concluídas</span>
                        </div>
                        <div class="bg-gray-200 rounded-full h-2.5">
                            <div class="bg-senai-green h-2.5 rounded-full transition-all" style="width:33%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">33% concluído</p>
                    </div>
                </div>
            </div>

            <!-- Curso 2 — não iniciado -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 flex gap-5 p-5 items-center opacity-75">
                <div class="bg-gradient-to-br from-orange-400 to-red-500 w-24 h-20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="text-4xl">🐘</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2 py-0.5 rounded">Back-end</span>
                            <h3 class="font-bold text-gray-800 text-base mt-1">PHP para Iniciantes</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Inscrito em 28/02/2025</p>
                        </div>
                        <a href="curso.php" class="border-2 border-senai-blue text-senai-blue text-xs font-bold px-5 py-2 rounded-lg hover:bg-blue-50 transition flex-shrink-0">
                            Começar
                        </a>
                    </div>
                    <!-- Barra de progresso -->
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                            <span>Ainda não iniciado</span>
                            <span class="text-gray-400">0 / 5 aulas concluídas</span>
                        </div>
                        <div class="bg-gray-200 rounded-full h-2.5">
                            <div class="bg-gray-300 h-2.5 rounded-full" style="width:0%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">0% concluído</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- SEÇÃO: Explore mais -->
        <div class="mt-10 bg-senai-blue rounded-2xl p-6 text-white text-center">
            <h3 class="font-extrabold text-lg mb-1">Quer aprender mais?</h3>
            <p class="text-blue-200 text-sm mb-4">Temos outros cursos disponíveis no catálogo.</p>
            <a href="cursos.php" class="inline-block bg-white text-senai-blue font-bold px-6 py-2.5 rounded-lg text-sm hover:bg-blue-50 transition">
                Ver todos os cursos
            </a>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-400 text-center py-4 text-xs mt-auto">
        SENAI — Sistema EAD &nbsp;|&nbsp; © 2025 Todos os direitos reservados
    </footer>

</body>
</html>
