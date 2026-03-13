<?php
session_start();
require_once "includes/logado.php";
require_once "includes/conexao.php";

$usuario_id = $_SESSION["usuario_id"];
$aula_id = $_GET["id"] ?? 0;

// Busca os dados na aula atual
$sql_aula = "SELECT * FROM aulas WHERE id = '$aula_id'";
$res_aula = mysqli_query($conexao, $sql_aula);

// Se a aula não existe ele chuta o aluno para 'meus_cursos'
if (mysqli_num_rows($res_aula) === 0){
    header("Location: meus_cursos.php");
    exit;
}

// Associa o resultado buscamos no banco, a variavel 'aula'
$aula = mysqli_fetch_assoc($res_aula);
// Associa a aula ao modulo do curso
$modulo_id = $aula['modulo_id'];

// Busca o módulo para saber de qual curso é esse módulo
$sql_modulo = "SELECT * FROM modulos WHERE id = '$modulo_id'";
$res_modulo = mysqli_query($conexao, $sql_modulo);
$modulo = mysqli_fetch_assoc($res_modulo);

// CRIA A VARIÁVEL QUE FALTAVA: Pega o ID do curso de dentro do módulo
$curso_id = $modulo['curso_id']; 

// Busca os dados do curso (Útil para usar no HTML depois)
$sql_curso = "SELECT * FROM cursos WHERE id = '$curso_id'";
$res_curso = mysqli_query($conexao, $sql_curso);
$curso = mysqli_fetch_assoc($res_curso);

// SEGURANÇA: Verifica se o aluno realmente tem acesso a este curso
$sql_matricula = "SELECT id FROM inscricoes WHERE usuario_id = '$usuario_id' AND curso_id = '$curso_id'";
if (mysqli_num_rows(mysqli_query($conexao, $sql_matricula)) === 0) {
    header("Location: meus_cursos.php");
    exit;
}

$link_video = $aula['video_url'] ?? '';

// O "SUPER DETETIVE" (Expressão Regular)
if (!empty($link_video)) {
    // Procura o ID do vídeo em qualquer formato de link do YouTube (watch?v=, youtu.be, embed, etc)
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link_video, $match)) {
        $video_id = $match[1]; // Pega exatamente as 11 letras do ID
        $link_video = "https://www.youtube.com/embed/" . $video_id; // Monta o link perfeito
    }
}

// Descobre qual é o próximo de acordo com a própria ordem do banco.
$ordem_atual = $aula['ordem'];

$sql_ant = "SELECT id FROM aulas WHERE modulo_id = '$modulo_id' AND ordem < '$ordem_atual' ORDER BY ordem DESC LIMIT 1";
$res_ant = mysqli_query($conexao, $sql_ant);
$id_anterior = (mysqli_num_rows($res_ant) > 0) ? mysqli_fetch_assoc($res_ant)['id'] : null;

$sql_prox = "SELECT id FROM aulas WHERE modulo_id = '$modulo_id' AND ordem > '$ordem_atual' ORDER BY ordem ASC LIMIT 1";
$res_prox = mysqli_query($conexao, $sql_prox);
$id_proxima = (mysqli_num_rows($res_prox) > 0) ? mysqli_fetch_assoc($res_prox)['id'] : null;

// LÓGICA DO BOTÃO "MARCAR COMO CONCLUÍDA"
$ja_concluiu = false;

// Verifica se o aluno já concluiu essa aula no passado (muda o visual do botão)
$sql_check = "SELECT id FROM historico WHERE usuario_id = '$usuario_id' AND aula_id = '$aula_id'";
if (mysqli_num_rows(mysqli_query($conexao, $sql_check)) > 0) {
    $ja_concluiu = true;
}

// Se o formulário do botão for enviado (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['marcar_concluida'])) {
    
    // Só insere se ele não tiver concluído ainda (evita duplicar no banco)
    if (!$ja_concluiu) {
        $sql_concluir = "INSERT INTO historico (usuario_id, aula_id) VALUES ('$usuario_id', '$aula_id')";
        mysqli_query($conexao, $sql_concluir);
    }
    
    // Recarrega a página para o botão ficar verde e travar
    header("Location: aula.php?id=$aula_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $aula['titulo']; ?> — EAD SENAI</title>
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
 
    <nav class="bg-gray-800 border-b border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-2.5 flex items-center gap-4">
            <a href="index.php" class="flex items-center gap-1.5 text-white font-extrabold text-base">🎓 EAD SENAI</a>
            <span class="text-gray-600">/</span>
            
            <a href="curso.php?id=<?php echo $curso_id; ?>" class="text-gray-400 hover:text-white text-sm transition">
                <?php echo $curso['titulo']; ?>
            </a>
            <span class="text-gray-600">/</span>
            <span class="text-gray-300 text-sm"><?php echo $modulo['titulo']; ?></span>
            
            <div class="flex-1"></div>
            <a href="meus_cursos.php" class="text-gray-400 hover:text-white text-xs transition">← Meus Cursos</a>
            <a href="logout.php" class="bg-senai-red text-white text-xs font-semibold px-3 py-1.5 rounded hover:bg-red-700 transition ml-2">Sair</a>
        </div>
    </nav>

    <div class="flex flex-1 max-w-7xl mx-auto w-full">

        <aside class="w-72 bg-gray-800 border-r border-gray-700 flex-shrink-0 overflow-y-auto hidden lg:block" style="height: calc(100vh - 44px); position: sticky; top: 44px;">
            <div class="p-4">
                <h3 class="text-white font-bold text-sm mb-1"><?php echo $curso['titulo']; ?></h3>
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex-1 bg-gray-700 rounded-full h-1.5">
                        <div class="bg-senai-green h-1.5 rounded-full" style="width:33%"></div>
                    </div>
                    <span class="text-xs text-gray-400">33%</span>
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-white mb-2 uppercase tracking-wide">
                        <span class="w-5 h-5 bg-senai-blue rounded-full flex items-center justify-center text-xs">1</span>
                        <?php echo $modulo['titulo']; ?>
                    </div>
                    <ul class="space-y-1 pl-2">
                        <li class="flex items-center gap-2 py-1.5 px-2 rounded bg-senai-blue text-xs text-white cursor-pointer">
                            <span class="w-4 h-4 bg-white/30 rounded-full flex items-center justify-center flex-shrink-0" style="font-size:9px">▶</span>
                            <span class="font-semibold"><?php echo $aula['titulo']; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto">

            <div class="bg-black aspect-video flex items-center justify-center max-h-96 lg:max-h-none w-full relative shadow-inner">
                <?php if (!empty($link_video)): ?>
                    <iframe 
                        src="<?php echo $link_video; ?>" 
                        title="<?php echo $aula['titulo']; ?>" 
                        class="absolute top-0 left-0 w-full h-full"
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                <?php else: ?>
                    <div class="text-center text-gray-500 p-8">
                        <span class="text-4xl">📴</span>
                        <p class="text-sm text-gray-400 mt-2">Vídeo indisponível.</p>
                        <p class="text-xs text-gray-600 mt-1">O link do vídeo não foi cadastrado.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white p-6 lg:p-8">

                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded">
                                <?php echo $modulo['titulo']; ?>
                            </span>
                        </div>
                        <h1 class="text-2xl font-extrabold text-gray-800">
                            <?php echo $aula['titulo']; ?>
                        </h1>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1.5 rounded-full flex-shrink-0">
                        Em andamento
                    </span>
                </div>

                <?php if (!empty($aula['descricao'])): ?>
                <div class="bg-gray-50 rounded-xl p-5 mb-6 text-sm text-gray-600 leading-relaxed border border-gray-100">
                    <?php echo nl2br($aula['descricao']); ?>
                </div>
                <?php endif; ?>

                <div class="flex items-center gap-3 flex-wrap">
                    
                    <?php if ($id_anterior): ?>
                        <a href="aula.php?id=<?php echo $id_anterior; ?>" class="flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">
                            ← Aula Anterior
                        </a>
                    <?php else: ?>
                        <a href="curso.php?id=<?php echo $curso_id; ?>" class="flex items-center gap-1.5 bg-gray-100 text-gray-400 text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">
                            ← Voltar ao Curso
                        </a>
                    <?php endif; ?>


                       <?php if ($ja_concluiu): ?>        
                        <span class="flex items-center gap-2 bg-green-50 text-green-700 border border-green-200 text-sm font-bold px-6 py-2.5 rounded-lg cursor-default shadow-sm">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            Aula Concluída
                        </span>

                    <?php else: ?>
                        
                        <form action="" method="post" class="inline">
                            <input type="hidden" name="marcar_concluida" value="1">
                            
                            <button type="submit" class="flex items-center gap-2 bg-senai-green text-white text-sm font-bold px-6 py-2.5 rounded-lg hover:bg-green-700 hover:shadow-lg transform transition active:scale-95">
                                <div class="w-4 h-4 rounded-full border-2 border-white opacity-80"></div>
                                Marcar como Concluída
                            </button>
                        </form>

                    <?php endif; ?>
                    <div class="flex-1"></div>
                    
                    <?php if ($id_proxima): ?>
                        <a href="aula.php?id=<?php echo $id_proxima; ?>" class="flex items-center gap-1.5 bg-senai-blue text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-senai-blue-dark transition ml-auto shadow-md">
                            Próxima Aula →
                        </a>
                    <?php else: ?>
                        <span class="flex items-center gap-1.5 bg-senai-green text-white text-sm font-bold px-6 py-2.5 rounded-lg shadow-md cursor-default ml-auto">
                            🎉 Módulo Concluído!
                        </span>
                    <?php endif; ?>
                </div>

            </div>
        </main>

    </div>

</body>
</html>