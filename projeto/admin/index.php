<?php
// Iniciar a sessão
session_start();

// Incluir conexão e verificar se está logado
require_once "../includes/conexao.php";
require_once "../includes/logado_admin.php";

// 1. Contar total de Cursos
$sql_cursos = "SELECT COUNT(id) AS total FROM cursos";
$res_cursos = mysqli_query($conexao, $sql_cursos);
$total_cursos = mysqli_fetch_assoc($res_cursos)['total'];

// 2. Contar total de Módulos
$sql_modulos = "SELECT COUNT(id) AS total FROM modulos";
$res_modulos = mysqli_query($conexao, $sql_modulos);
$total_modulos = mysqli_fetch_assoc($res_modulos)['total'];

// 3. Contar total de Aulas
$sql_aulas = "SELECT COUNT(id) AS total FROM aulas";
$res_aulas = mysqli_query($conexao, $sql_aulas);
$total_aulas = mysqli_fetch_assoc($res_aulas)['total'];

// (Opcional) Buscar os últimos 5 cursos para a tabela
$sql_ultimos_cursos = "SELECT * FROM cursos ORDER BY id DESC LIMIT 5";
$res_ultimos_cursos = mysqli_query($conexao, $sql_ultimos_cursos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Painel Admin | EAD SENAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { senai: { red:'#C0392B', blue:'#34679A', 'blue-dark':'#2C5A85', orange:'#E67E22', green:'#27AE60' } } } }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .nav-link { display:flex; align-items:center; gap:8px; padding:8px 12px; border-radius:6px; font-size:13px; cursor:pointer; transition:background .15s; color:#cbd5e1; }
        .nav-link:hover { background:rgba(255,255,255,.08); color:#fff; }
        .nav-link.active { background:rgba(255,255,255,.15); color:#fff; font-weight:600; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- SIDEBAR ADMIN -->
    <aside class="w-56 bg-gray-900 min-h-screen flex flex-col flex-shrink-0">
        <!-- Logo -->
        <div class="px-4 py-5 border-b border-gray-700">
            <p class="text-white font-extrabold text-base">🎓 EAD SENAI</p>
            <p class="text-gray-500 text-xs mt-0.5">Painel Administrativo</p>
        </div>
        <!-- Info admin -->
        <div class="px-4 py-3 border-b border-gray-700">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-senai-blue rounded-full flex items-center justify-center text-white text-xs font-bold">A</div>
                <div>
                    <p class="text-white text-xs font-semibold">Administrador</p>
                    <p class="text-gray-500 text-xs"><?php echo $dados_usuario['email']; ?></p>
                    
                </div>
            </div>
        </div>
        <!-- Menu -->
        <nav class="flex-1 p-3 space-y-1">
            <a href="index.php"      class="nav-link active">📊 <span>Dashboard</span></a>
            <a href="cursos.php"     class="nav-link">📚 <span>Cursos</span></a>
            <div class="pt-2 border-t border-gray-700 mt-2">
                <a href="../meus_cursos.php" class="nav-link">👁 <span>Ver site</span></a>
                <a href="../login.php"       class="nav-link text-red-400 hover:text-red-300">🚪 <span>Sair</span></a>
            </div>
        </nav>
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="flex-1 flex flex-col">

        <!-- TOPBAR -->
        <div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-extrabold text-gray-800">Dashboard</h1>
                <p class="text-sm text-gray-500">Visão geral do sistema EAD</p>
            </div>
            <span class="text-xs text-gray-400">Segunda-feira, 01 de Março de 2025</span>
        </div>

        <div class="p-6 flex-1">

            <!-- CARDS DE TOTAIS -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl p-5 shadow-sm border-t-4 border-senai-blue">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl">📚</span>
                        <span class="text-xs text-gray-400 bg-blue-50 px-2 py-0.5 rounded">Total</span>
                    </div>
                    <p class="text-3xl font-extrabold text-senai-blue"><?php echo $total_cursos; ?></p>
                    <p class="text-sm text-gray-500 mt-1">Cursos cadastrados</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border-t-4 border-senai-orange">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl">📦</span>
                        <span class="text-xs text-gray-400 bg-orange-50 px-2 py-0.5 rounded">Total</span>
                    </div>
                    <p class="text-3xl font-extrabold text-senai-orange"><?php echo $total_modulos; ?></p>
                    <p class="text-sm text-gray-500 mt-1">Módulos cadastrados</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border-t-4 border-senai-red">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl">🎬</span>
                        <span class="text-xs text-gray-400 bg-red-50 px-2 py-0.5 rounded">Total</span>
                    </div>
                    <p class="text-3xl font-extrabold text-senai-red"><?php echo $total_aulas; ?></p>
                    <p class="text-sm text-gray-500 mt-1">Aulas cadastradas</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border-t-4 border-senai-green">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl">👥</span>
                        <span class="text-xs text-gray-400 bg-green-50 px-2 py-0.5 rounded">Total</span>
                    </div>
                    <p class="text-3xl font-extrabold text-senai-green">24</p>
                    <p class="text-sm text-gray-500 mt-1">Inscrições realizadas</p>
                </div>
            </div>

            <!-- AÇÕES RÁPIDAS + TABELA -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Ações Rápidas -->
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <h2 class="font-bold text-gray-700 mb-4 text-sm">Ações Rápidas</h2>
                    <div class="space-y-2">
                        <a href="curso_form.php" class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition cursor-pointer">
                            <span class="w-8 h-8 bg-senai-blue rounded-lg flex items-center justify-center text-white text-sm">+</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Novo Curso</p>
                                <p class="text-xs text-gray-400">Cadastrar um curso</p>
                            </div>
                        </a>
                        <a href="modulo_form.php" class="flex items-center gap-3 p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition cursor-pointer">
                            <span class="w-8 h-8 bg-senai-orange rounded-lg flex items-center justify-center text-white text-sm">+</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Novo Módulo</p>
                                <p class="text-xs text-gray-400">Adicionar a um curso</p>
                            </div>
                        </a>
                        <a href="aula_form.php" class="flex items-center gap-3 p-3 bg-red-50 hover:bg-red-100 rounded-lg transition cursor-pointer">
                            <span class="w-8 h-8 bg-senai-red rounded-lg flex items-center justify-center text-white text-sm">+</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Nova Aula</p>
                                <p class="text-xs text-gray-400">Adicionar a um módulo</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Cursos cadastrados -->
                <div class="bg-white rounded-xl shadow-sm p-5 lg:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-gray-700 text-sm">Cursos Cadastrados</h2>
                        <a href="cursos.php" class="text-xs text-senai-blue underline">Ver todos</a>
                    </div>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-gray-400 uppercase">
                                <th class="text-left pb-2 font-semibold">Curso</th>
                                <th class="text-center pb-2 font-semibold">Módulos</th>
                                <th class="text-center pb-2 font-semibold">Aulas</th>
                                <th class="text-center pb-2 font-semibold">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            <?php while ($curso = mysqli_fetch_assoc($res_ultimos_cursos)): ?>
                                <tr>
                                <td class="py-2.5 font-medium text-gray-700 flex items-center gap-2">
                                     <?php if (!empty($curso['capa'])): ?>
                                        <img src="../uploads/<?php echo $curso['capa']; ?>" alt="Capa" class="w-8 h-8 rounded object-cover border border-gray-200">
                                        <?php else: ?>
                                            <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center text-sm">🌐</div>
    
                                            <?php endif; ?>
                                            <span><?php echo $curso['titulo']; ?></span>
                                         </td>
                                      </tr>
                              <?php endwhile; ?>
                         </tbody>

                    </table>
                </div>

            </div>

        </div>
    </main>

</body>
</html>
