<?php
session_start();
require_once "includes/logado.php";
require_once "includes/conexao.php";

$usuario_id = $_SESSION["usuario_id"];
$aula_id = $_GET["id"] ?? 0;

// 1. Busca os dados da aula atual
$sql_aula = "SELECT * FROM aulas WHERE id = '$aula_id'";
$res_aula = mysqli_query($conexao, $sql_aula);
if (mysqli_num_rows($res_aula) === 0){
    header("Location: meus_cursos.php");
    exit;
}
$aula = mysqli_fetch_assoc($res_aula);
$modulo_id = $aula['modulo_id'];

// 2. Busca Módulo e Curso
$sql_modulo = "SELECT * FROM modulos WHERE id = '$modulo_id'";
$modulo = mysqli_fetch_assoc(mysqli_query($conexao, $sql_modulo));
$curso_id = $modulo['curso_id']; 

$sql_curso = "SELECT * FROM cursos WHERE id = '$curso_id'";
$curso = mysqli_fetch_assoc(mysqli_query($conexao, $sql_curso));

// 3. SEGURANÇA
$sql_matricula = "SELECT id FROM inscricoes WHERE usuario_id = '$usuario_id' AND curso_id = '$curso_id'";
if (mysqli_num_rows(mysqli_query($conexao, $sql_matricula)) === 0) {
    header("Location: meus_cursos.php");
    exit;
}

// -----------------------------------------------------------
// PARTE 1: O TOGGLE DE CONCLUSÃO (LIGA / DESLIGA)
// -----------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_conclusao'])) {
    
    // O detetive verifica se o aluno já marcou essa aula
    $check_sql = "SELECT id FROM historico WHERE usuario_id = '$usuario_id' AND aula_id = '$aula_id'";
    $check_res = mysqli_query($conexao, $check_sql);
    
    if (mysqli_num_rows($check_res) > 0) {
        // Se já tem no banco, o botão funciona como DESLIGAR (Delete)
        $del_sql = "DELETE FROM historico WHERE usuario_id = '$usuario_id' AND aula_id = '$aula_id'";
        mysqli_query($conexao, $del_sql);
    } else {
        // Se não tem no banco, o botão funciona como LIGAR (Insert)
        $ins_sql = "INSERT INTO historico (usuario_id, aula_id) VALUES ('$usuario_id', '$aula_id')";
        mysqli_query($conexao, $ins_sql);
    }
    
    // Dá um "F5" invisível para a página atualizar com a cor certa
    header("Location: aula.php?id=$aula_id");
    exit;
}

// Verifica o estado final para saber de que cor o botão e os ícones vão carregar
$ja_concluiu = false;
$sql_estado = "SELECT id FROM historico WHERE usuario_id = '$usuario_id' AND aula_id = '$aula_id'";
if (mysqli_num_rows(mysqli_query($conexao, $sql_estado)) > 0) {
    $ja_concluiu = true;
}

// -----------------------------------------------------------
// PARTE 2: A MATEMÁTICA DO PROGRESSO
// -----------------------------------------------------------

// A) Total de aulas que este curso tem (Buscando em todos os módulos dele)
$sql_tot_aulas = "SELECT COUNT(aulas.id) AS total FROM aulas 
                  INNER JOIN modulos ON aulas.modulo_id = modulos.id 
                  WHERE modulos.curso_id = '$curso_id'";
$total_aulas = mysqli_fetch_assoc(mysqli_query($conexao, $sql_tot_aulas))['total'];

// B) Quantas aulas ESTE aluno terminou NESTE curso
$sql_concluidas = "SELECT COUNT(historico.id) AS concluidas FROM historico
                   INNER JOIN aulas ON historico.aula_id = aulas.id
                   INNER JOIN modulos ON aulas.modulo_id = modulos.id
                   WHERE modulos.curso_id = '$curso_id' AND historico.usuario_id = '$usuario_id'";
$aulas_concluidas = mysqli_fetch_assoc(mysqli_query($conexao, $sql_concluidas))['concluidas'];

// C) Regra de 3 (Garante que se tiver 0 aulas, não dê erro matemático no PHP)
$porcentagem = ($total_aulas > 0) ? round(($aulas_concluidas / $total_aulas) * 100) : 0;

// D) Cria um "dicionário" (array) com todas as aulas que o aluno já fez para pintar a sidebar
$aulas_feitas = [];
$res_feitas = mysqli_query($conexao, "SELECT aula_id FROM historico WHERE usuario_id = '$usuario_id'");
while($row = mysqli_fetch_assoc($res_feitas)) {
    $aulas_feitas[] = $row['aula_id'];
}

// 4. Tratamento do link de vídeo e lógica de próximo/anterior...
$link_video = $aula['video_url'] ?? '';
if (!empty($link_video) && preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link_video, $match)) {
    $link_video = "https://www.youtube.com/embed/" . $match[1];
}

$ordem_atual = $aula['ordem'];
$sql_ant = "SELECT id FROM aulas WHERE modulo_id = '$modulo_id' AND ordem < '$ordem_atual' ORDER BY ordem DESC LIMIT 1";
$id_anterior = (mysqli_num_rows($res_ant = mysqli_query($conexao, $sql_ant)) > 0) ? mysqli_fetch_assoc($res_ant)['id'] : null;

$sql_prox = "SELECT id FROM aulas WHERE modulo_id = '$modulo_id' AND ordem > '$ordem_atual' ORDER BY ordem ASC LIMIT 1";
$id_proxima = (mysqli_num_rows($res_prox = mysqli_query($conexao, $sql_prox)) > 0) ? mysqli_fetch_assoc($res_prox)['id'] : null;

// Descobre quem é a aula anterior (continua igual)
$ordem_atual = $aula['ordem'];
$sql_ant = "SELECT id FROM aulas WHERE modulo_id = '$modulo_id' AND ordem < '$ordem_atual' ORDER BY ordem DESC LIMIT 1";
$res_ant = mysqli_query($conexao, $sql_ant);
$id_anterior = (mysqli_num_rows($res_ant) > 0) ? mysqli_fetch_assoc($res_ant)['id'] : null;


// A SUPER LÓGICA DA PRÓXIMA AULA / PRÓXIMO MÓDULO
$id_proxima = null;
$texto_proxima = "Próxima Aula →"; // Texto padrão

// 1. Tenta achar a próxima aula no MESMO módulo
$sql_prox = "SELECT id FROM aulas WHERE modulo_id = '$modulo_id' AND ordem > '$ordem_atual' ORDER BY ordem ASC LIMIT 1";
$res_prox = mysqli_query($conexao, $sql_prox);

if (mysqli_num_rows($res_prox) > 0) {
    // Achou! É só uma aula normal do mesmo módulo.
    $id_proxima = mysqli_fetch_assoc($res_prox)['id'];
} else {
    // 2. Não achou? Então o módulo acabou. Vamos procurar o PRÓXIMO MÓDULO do curso.
    $ordem_mod_atual = $modulo['ordem'];
    $sql_prox_mod = "SELECT id FROM modulos WHERE curso_id = '$curso_id' AND ordem > '$ordem_mod_atual' ORDER BY ordem ASC LIMIT 1";
    $res_prox_mod = mysqli_query($conexao, $sql_prox_mod);

    if (mysqli_num_rows($res_prox_mod) > 0) {
        $prox_mod_id = mysqli_fetch_assoc($res_prox_mod)['id'];

        // 3. Achou o próximo módulo! Agora pega a PRIMEIRA aula dele.
        $sql_pri_aula = "SELECT id FROM aulas WHERE modulo_id = '$prox_mod_id' ORDER BY ordem ASC LIMIT 1";
        $res_pri_aula = mysqli_query($conexao, $sql_pri_aula);

        if (mysqli_num_rows($res_pri_aula) > 0) {
            $id_proxima = mysqli_fetch_assoc($res_pri_aula)['id'];
            $texto_proxima = "Próximo Módulo 🚀"; // Muda o texto do botão!
        }
    }
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
                
                <div class="flex items-center gap-2 mb-6 mt-2">
                    <div class="flex-1 bg-gray-700 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-senai-green h-1.5 rounded-full transition-all duration-1000" style="width:<?php echo $porcentagem; ?>%"></div>
                    </div>
                    <span class="text-xs text-gray-400 font-mono"><?php echo $porcentagem; ?>%</span>
                </div>

                <?php 
                $res_todos_mod = mysqli_query($conexao, "SELECT * FROM modulos WHERE curso_id = '$curso_id' ORDER BY ordem ASC");
                while ($mod = mysqli_fetch_assoc($res_todos_mod)): 
                    $mod_id = $mod['id'];
                ?>
                <div class="mb-5">
                    <div class="flex items-center gap-2 text-xs font-bold text-white mb-2 uppercase tracking-wide opacity-80">
                        <span class="w-5 h-5 bg-gray-700 rounded-full flex items-center justify-center text-xs"> <?php echo $mod['ordem']; ?> </span>
                        <?php echo $mod['titulo']; ?>
                    </div>
                    
                    <ul class="space-y-1 pl-2">
                        <?php 
                        $res_aulas_side = mysqli_query($conexao, "SELECT * FROM aulas WHERE modulo_id = '$mod_id' ORDER BY ordem ASC");
                        while ($aula_side = mysqli_fetch_assoc($res_aulas_side)):
                            // Descobre os estados visuais da aula atual do loop
                            $is_atual = ($aula_side['id'] == $aula_id);
                            // O array mágico que verifica se o ID da aula está na lista de concluídas
                            $is_concluida = in_array($aula_side['id'], $aulas_feitas); 
                        ?>
                        
                        <a href="aula.php?id=<?php echo $aula_side['id']; ?>" class="block">
                            <li class="flex items-center gap-2 py-1.5 px-2 rounded cursor-pointer transition 
                                <?php echo $is_atual ? 'bg-senai-blue text-white' : 'hover:bg-gray-700 text-gray-400'; ?>">
                                
                                <?php if ($is_atual): ?>
                                    <span class="w-4 h-4 bg-white/30 rounded-full flex items-center justify-center flex-shrink-0" style="font-size:9px">▶</span>
                                <?php elseif ($is_concluida): ?>
                                    <span class="w-4 h-4 bg-senai-green rounded-full flex items-center justify-center flex-shrink-0 text-white" style="font-size:9px">✓</span>
                                <?php else: ?>
                                    <span class="w-4 h-4 border border-gray-500 rounded-full flex-shrink-0"></span>
                                <?php endif; ?>
                                
                                <span class="text-xs <?php echo $is_atual ? 'font-semibold' : ''; ?> <?php echo (!$is_atual && $is_concluida) ? 'text-green-400' : ''; ?>">
                                    <?php echo $aula_side['titulo']; ?>
                                </span>
                            </li>
                        </a>

                        <?php endwhile; ?>
                    </ul>
                </div>
                <?php endwhile; ?>

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
 
                    <form action="" method="post" class="inline">
                        <input type="hidden" name="toggle_conclusao" value="1">
                        
                        <?php if ($ja_concluiu): ?>
                            <button type="submit" class="group flex items-center gap-1.5 bg-green-50 text-green-700 border border-green-300 text-sm font-bold px-6 py-2.5 rounded-lg hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition shadow-sm">
                                <span class="group-hover:hidden">✓ Concluída</span>
                                <span class="hidden group-hover:inline">✕ Desmarcar Aula</span>
                            </button>
                        <?php else: ?>
                            <button type="submit" class="flex items-center gap-1.5 bg-senai-green text-white text-sm font-bold px-6 py-2.5 rounded-lg hover:bg-green-600 transition shadow transform active:scale-95">
                                <div class="w-4 h-4 rounded-full border-2 border-white opacity-80"></div>
                                Marcar como Concluída
                            </button>
                        <?php endif; ?>
                    </form>      

                    <div class="flex-1"></div>             
                    <?php if ($id_proxima): ?>
                        <a href="aula.php?id=<?php echo $id_proxima; ?>" class="flex items-center gap-1.5 bg-senai-blue text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-senai-blue-dark transition ml-auto shadow-md">
                            <?php echo $texto_proxima; ?>
                        </a>
                    <?php else: ?>
                        <span class="flex items-center gap-1.5 bg-yellow-400 text-yellow-900 text-sm font-extrabold px-6 py-2.5 rounded-lg shadow-md cursor-default ml-auto">
                            🏆 Curso Concluído!
                        </span>
                    <?php endif; ?>
                </div>

            </div>
        </main>

    </div>

</body>
</html>