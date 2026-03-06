<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EAD SENAI — Plataforma de Ensino</title>
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
<body class="bg-white">

    <!-- NAVBAR -->
    <nav class="bg-senai-blue shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-2 text-white font-extrabold text-lg">
                🎓 <span>EAD SENAI</span>
            </a>
            <div class="flex items-center gap-6 text-sm">
                <a href="#cursos" class="text-blue-200 hover:text-white transition">Cursos</a>
                <a href="#sobre" class="text-blue-200 hover:text-white transition">Sobre</a>
                <a href="login.php" class="border border-white text-white px-4 py-1.5 rounded hover:bg-white hover:text-senai-blue transition font-semibold">Entrar</a>
                <a href="cadastro.php" class="bg-senai-green text-white px-4 py-1.5 rounded hover:bg-green-600 transition font-semibold">Cadastrar-se</a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="bg-gradient-to-br from-senai-blue-dark via-senai-blue to-blue-500 text-white py-24 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">Plataforma de Ensino a Distância</span>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-5">
                Aprenda no seu ritmo,<br>de qualquer lugar.
            </h1>
            <p class="text-lg text-blue-100 mb-8 max-w-xl mx-auto">
                Acesse cursos completos com módulos organizados e aulas em vídeo. Acompanhe seu progresso e evolua na sua carreira.
            </p>
            <div class="flex justify-center gap-4 flex-wrap">
                <a href="cadastro.php" class="bg-yellow-400 text-gray-900 font-bold px-8 py-3 rounded-lg text-sm hover:bg-yellow-300 transition shadow-lg">
                    Quero me Cadastrar — É Grátis!
                </a>
                <a href="#cursos" class="border-2 border-white/50 text-white font-semibold px-8 py-3 rounded-lg text-sm hover:bg-white/10 transition">
                    Ver Cursos Disponíveis
                </a>
            </div>
        </div>
    </section>

    <!-- STATS -->
    <section class="bg-senai-blue-dark text-white py-6">
        <div class="max-w-4xl mx-auto px-6 grid grid-cols-3 gap-6 text-center">
            <div>
                <p class="text-3xl font-extrabold text-yellow-400">3+</p>
                <p class="text-sm text-blue-200 mt-1">Cursos disponíveis</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-yellow-400">15+</p>
                <p class="text-sm text-blue-200 mt-1">Aulas em vídeo</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-yellow-400">100%</p>
                <p class="text-sm text-blue-200 mt-1">Online e gratuito</p>
            </div>
        </div>
    </section>

    <!-- CURSOS EM DESTAQUE -->
    <section id="cursos" class="py-16 px-6 bg-gray-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Cursos em Destaque</h2>
                <p class="text-gray-500">Escolha um curso, inscreva-se e comece a aprender hoje mesmo.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Card 1 -->
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden flex flex-col">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 h-36 flex items-center justify-center">
                        <span class="text-5xl">🌐</span>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded">Front-end</span>
                            <span class="text-xs text-gray-400">3 módulos · 9 aulas</span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base mb-2">HTML e CSS do Zero</h3>
                        <p class="text-sm text-gray-500 mb-4 flex-1">Aprenda a criar páginas web profissionais do início ao fim, com projetos práticos.</p>
                        <a href="cadastro.php" class="bg-senai-blue text-white text-sm font-semibold py-2 rounded-lg text-center hover:bg-senai-blue-dark transition">
                            Inscrever-se Grátis
                        </a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden flex flex-col">
                    <div class="bg-gradient-to-br from-orange-400 to-red-500 h-36 flex items-center justify-center">
                        <span class="text-5xl">🐘</span>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2 py-0.5 rounded">Back-end</span>
                            <span class="text-xs text-gray-400">2 módulos · 5 aulas</span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base mb-2">PHP para Iniciantes</h3>
                        <p class="text-sm text-gray-500 mb-4 flex-1">Introdução ao desenvolvimento back-end com PHP e MySQL. Do zero ao primeiro sistema.</p>
                        <a href="cadastro.php" class="bg-senai-blue text-white text-sm font-semibold py-2 rounded-lg text-center hover:bg-senai-blue-dark transition">
                            Inscrever-se Grátis
                        </a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden flex flex-col">
                    <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 h-36 flex items-center justify-center">
                        <span class="text-5xl">⚡</span>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-0.5 rounded">Front-end</span>
                            <span class="text-xs text-gray-400">4 módulos · 12 aulas</span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base mb-2">JavaScript Moderno (ES6+)</h3>
                        <p class="text-sm text-gray-500 mb-4 flex-1">Domine os fundamentos e recursos modernos do JavaScript para a web.</p>
                        <a href="cadastro.php" class="bg-senai-blue text-white text-sm font-semibold py-2 rounded-lg text-center hover:bg-senai-blue-dark transition">
                            Inscrever-se Grátis
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- COMO FUNCIONA -->
    <section id="sobre" class="py-16 px-6 bg-white">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Como Funciona?</h2>
            <p class="text-gray-500 mb-10">Em 3 passos simples você já está aprendendo.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-14 h-14 bg-senai-blue rounded-full flex items-center justify-center text-white text-2xl font-extrabold mx-auto mb-4">1</div>
                    <h3 class="font-bold text-gray-700 mb-1">Crie sua conta</h3>
                    <p class="text-sm text-gray-500">Cadastre-se gratuitamente com nome, e-mail e senha.</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 bg-senai-orange rounded-full flex items-center justify-center text-white text-2xl font-extrabold mx-auto mb-4">2</div>
                    <h3 class="font-bold text-gray-700 mb-1">Escolha um curso</h3>
                    <p class="text-sm text-gray-500">Navegue pelo catálogo e se inscreva nos cursos que deseja.</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 bg-senai-green rounded-full flex items-center justify-center text-white text-2xl font-extrabold mx-auto mb-4">3</div>
                    <h3 class="font-bold text-gray-700 mb-1">Assista e aprenda</h3>
                    <p class="text-sm text-gray-500">Acesse módulos, aulas e marque seu progresso a cada aula concluída.</p>
                </div>
            </div>
            <div class="mt-10">
                <a href="cadastro.php" class="bg-senai-blue text-white font-bold px-10 py-3 rounded-lg text-sm hover:bg-senai-blue-dark transition shadow">
                    Comece Agora — É Grátis
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-400 text-center py-6 text-sm">
        <p class="mb-1 font-semibold text-white">🎓 EAD SENAI</p>
        <p>Serviço Nacional de Aprendizagem Industrial — Plataforma de Ensino a Distância</p>
        <p class="text-xs mt-2 text-gray-600">© 2025 SENAI. Todos os direitos reservados.</p>
    </footer>

</body>
</html>
