<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tags Essenciais do HTML — EAD SENAI</title>
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
<body class="bg-gray-900 min-h-screen flex flex-col">
 
    <!-- NAVBAR ESCURA (modo aula) -->
    <nav class="bg-gray-800 border-b border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-2.5 flex items-center gap-4">
            <a href="index.php" class="flex items-center gap-1.5 text-white font-extrabold text-base">🎓 EAD SENAI</a>
            <span class="text-gray-600">/</span>
            <a href="curso.php" class="text-gray-400 hover:text-white text-sm transition">HTML e CSS do Zero</a>
            <span class="text-gray-600">/</span>
            <span class="text-gray-300 text-sm">Módulo 1</span>
            <div class="flex-1"></div>
            <a href="meus_cursos.php" class="text-gray-400 hover:text-white text-xs transition">← Meus Cursos</a>
            <a href="login.php" class="bg-senai-red text-white text-xs font-semibold px-3 py-1.5 rounded hover:bg-red-700 transition ml-2">Sair</a>
        </div>
    </nav>

    <!-- LAYOUT PRINCIPAL -->
    <div class="flex flex-1 max-w-7xl mx-auto w-full">

        <!-- SIDEBAR DE AULAS -->
        <aside class="w-72 bg-gray-800 border-r border-gray-700 flex-shrink-0 overflow-y-auto hidden lg:block" style="height: calc(100vh - 44px); position: sticky; top: 44px;">
            <div class="p-4">
                <h3 class="text-white font-bold text-sm mb-1">HTML e CSS do Zero</h3>
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex-1 bg-gray-700 rounded-full h-1.5">
                        <div class="bg-senai-green h-1.5 rounded-full" style="width:33%"></div>
                    </div>
                    <span class="text-xs text-gray-400">33%</span>
                </div>

                <!-- Módulo 1 -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-white mb-2 uppercase tracking-wide">
                        <span class="w-5 h-5 bg-senai-blue rounded-full flex items-center justify-center text-xs">1</span>
                        Introdução ao HTML
                    </div>
                    <ul class="space-y-1 pl-2">
                        <li class="flex items-center gap-2 py-1.5 px-2 rounded text-xs text-green-400 cursor-pointer hover:bg-gray-700">
                            <span class="w-4 h-4 bg-senai-green rounded-full flex items-center justify-center flex-shrink-0 text-white" style="font-size:9px">✓</span>
                            <span>O que é HTML?</span>
                            <span class="ml-auto text-gray-500">8:20</span>
                        </li>
                        <li class="flex items-center gap-2 py-1.5 px-2 rounded text-xs text-green-400 cursor-pointer hover:bg-gray-700">
                            <span class="w-4 h-4 bg-senai-green rounded-full flex items-center justify-center flex-shrink-0 text-white" style="font-size:9px">✓</span>
                            <span>Estrutura básica</span>
                            <span class="ml-auto text-gray-500">12:45</span>
                        </li>
                        <li class="flex items-center gap-2 py-1.5 px-2 rounded bg-senai-blue text-xs text-white cursor-pointer">
                            <span class="w-4 h-4 bg-white/30 rounded-full flex items-center justify-center flex-shrink-0" style="font-size:9px">▶</span>
                            <span class="font-semibold">Tags Essenciais</span>
                            <span class="ml-auto text-blue-200">15:10</span>
                        </li>
                    </ul>
                </div>

                <!-- Módulo 2 -->
                <div class="mb-4 opacity-50">
                    <div class="flex items-center gap-2 text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">
                        <span class="w-5 h-5 bg-gray-600 rounded-full flex items-center justify-center text-xs">2</span>
                        Estilizando com CSS
                    </div>
                    <ul class="space-y-1 pl-2">
                        <li class="flex items-center gap-2 py-1.5 px-2 rounded text-xs text-gray-500">
                            <span class="w-4 h-4 bg-gray-700 rounded-full flex-shrink-0"></span>
                            <span>Seletores CSS</span>
                            <span class="ml-auto">10:00</span>
                        </li>
                        <li class="flex items-center gap-2 py-1.5 px-2 rounded text-xs text-gray-500">
                            <span class="w-4 h-4 bg-gray-700 rounded-full flex-shrink-0"></span>
                            <span>Box Model</span>
                            <span class="ml-auto">14:30</span>
                        </li>
                        <li class="flex items-center gap-2 py-1.5 px-2 rounded text-xs text-gray-500">
                            <span class="w-4 h-4 bg-gray-700 rounded-full flex-shrink-0"></span>
                            <span>Flexbox na prática</span>
                            <span class="ml-auto">20:00</span>
                        </li>
                    </ul>
                </div>

                <!-- Módulo 3 -->
                <div class="opacity-40">
                    <div class="flex items-center gap-2 text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">
                        <span class="w-5 h-5 bg-gray-600 rounded-full flex items-center justify-center text-xs">3</span>
                        Projeto Final
                    </div>
                    <ul class="space-y-1 pl-2">
                        <li class="text-xs text-gray-500 px-2 py-1.5">○ Aula 1</li>
                        <li class="text-xs text-gray-500 px-2 py-1.5">○ Aula 2</li>
                        <li class="text-xs text-gray-500 px-2 py-1.5">○ Aula 3</li>
                    </ul>
                </div>

            </div>
        </aside>

        <!-- ÁREA PRINCIPAL DA AULA -->
        <main class="flex-1 overflow-y-auto">

            <!-- PLAYER DE VÍDEO -->
            <div class="bg-black aspect-video flex items-center justify-center max-h-96 lg:max-h-none w-full">
                <div class="text-center text-gray-500 p-8">
                    <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 cursor-pointer hover:bg-gray-700 transition">
                        <span class="text-4xl text-white ml-1">▶</span>
                    </div>
                    <p class="text-sm text-gray-400">Player de Vídeo</p>
                    <p class="text-xs text-gray-600 mt-1">iframe YouTube / Vimeo</p>
                    <p class="text-xs text-gray-700 mt-1 font-mono">video_url do banco de dados</p>
                </div>
            </div>

            <!-- INFORMAÇÕES DA AULA -->
            <div class="bg-white p-6 lg:p-8">

                <!-- Título e badge -->
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded">Módulo 1 · Aula 3</span>
                            <span class="text-gray-400 text-xs">⏱ 15:10</span>
                        </div>
                        <h1 class="text-2xl font-extrabold text-gray-800">Tags Essenciais do HTML</h1>
                    </div>
                    <!-- Status da aula -->
                    <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1.5 rounded-full flex-shrink-0">
                        Em andamento
                    </span>
                </div>

                <!-- Descrição -->
                <div class="bg-gray-50 rounded-xl p-4 mb-6 text-sm text-gray-600 leading-relaxed">
                    Nesta aula você aprenderá as principais tags HTML utilizadas na construção de páginas web:
                    headings, parágrafos, links, imagens, listas, tabelas e muito mais. Ao final, você saberá
                    estruturar qualquer página de forma semântica e acessível.
                </div>

                <!-- AÇÕES -->
                <div class="flex items-center gap-3 flex-wrap">
                    <a href="curso.php" class="flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">
                        ← Aula Anterior
                    </a>

                    <!-- MARCAR COMO CONCLUÍDA -->
                    <form action="aula.php" method="post" class="inline">
                        <button type="submit"
                            class="flex items-center gap-1.5 bg-senai-green text-white text-sm font-bold px-6 py-2.5 rounded-lg hover:bg-green-600 transition shadow">
                            ✓ Marcar como Concluída
                        </button>
                    </form>

                    <a href="curso.php" class="flex items-center gap-1.5 bg-senai-blue text-white text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-senai-blue-dark transition ml-auto">
                        Próxima Aula →
                    </a>
                </div>

                <!-- SE JÁ CONCLUÍDA (estado alternativo) -->
                <div class="mt-5 bg-green-50 border border-green-300 rounded-xl p-4 flex items-center gap-3 hidden">
                    <div class="w-10 h-10 bg-senai-green rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">✓</span>
                    </div>
                    <div>
                        <p class="font-bold text-green-800 text-sm">Aula concluída!</p>
                        <p class="text-xs text-green-700">Concluída em 01/03/2025 às 14:32. Continue para a próxima aula.</p>
                    </div>
                    <a href="curso.php" class="ml-auto bg-senai-green text-white text-xs font-bold px-4 py-2 rounded-lg">Próxima Aula →</a>
                </div>

                <!-- DIVIDER -->
                <hr class="my-6 border-gray-200">

                <!-- NAVEGAÇÃO DO MÓDULO -->
                <div>
                    <h3 class="font-bold text-gray-700 text-sm mb-3">Outras aulas deste módulo</h3>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                            <span class="w-6 h-6 bg-senai-green rounded-full flex items-center justify-center text-white text-xs flex-shrink-0">✓</span>
                            <span class="text-sm text-gray-600">O que é HTML?</span>
                            <span class="ml-auto text-xs text-gray-400">8:20</span>
                            <a href="aula.php" class="text-xs text-senai-blue underline">Rever</a>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                            <span class="w-6 h-6 bg-senai-green rounded-full flex items-center justify-center text-white text-xs flex-shrink-0">✓</span>
                            <span class="text-sm text-gray-600">Estrutura básica de uma página</span>
                            <span class="ml-auto text-xs text-gray-400">12:45</span>
                            <a href="aula.php" class="text-xs text-senai-blue underline">Rever</a>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-blue-50 border border-senai-blue rounded-lg">
                            <span class="w-6 h-6 bg-senai-blue rounded-full flex items-center justify-center text-white text-xs flex-shrink-0">▶</span>
                            <span class="text-sm font-semibold text-gray-800">Tags Essenciais do HTML</span>
                            <span class="ml-auto text-xs text-gray-400">15:10</span>
                            <span class="text-xs text-senai-blue font-semibold">Atual</span>
                        </div>
                    </div>
                </div>

            </div>
        </main>

    </div>

</body>
</html>
