<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco
require_once "../includes/conexao.php";
require_once "../includes/logado_admin.php";

// Tentar pegar o ID do curso pela URL
$curso_id = $_GET['curso_id'] ?? null;

// Se não tiver curso_id na URL, chuta de volta pra lista de cursos
if (!$curso_id) {
    header("Location: cursos.php");
    exit;
}

// Buscar o NOME do curso para colocar no título da página
$sql_nome_curso = "SELECT titulo FROM cursos WHERE id = '$curso_id'";
$res_nome_curso = mysqli_query($conexao, $sql_nome_curso);
$dados_curso = mysqli_fetch_assoc($res_nome_curso);
$nome_do_curso = $dados_curso['titulo'] ?? "Curso Desconhecido";

// Buscar APENAS os módulos que pertencem a este curso específico
$sql_modulos = "SELECT * FROM modulos WHERE curso_id = '$curso_id' ORDER BY ordem ASC";
$resultado_modulos = mysqli_query($conexao, $sql_modulos);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulos: <?php echo $nome_do_curso; ?> — Admin | EAD SENAI</title>
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
        .form-input { width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 14px; font-size:14px; outline:none; transition:border .15s; }
        .form-input:focus { border-color:#34679A; box-shadow:0 0 0 3px rgba(52,103,154,.15); }
        .form-label { display:block; font-size:12px; font-weight:600; color:#6b7280; margin-bottom:6px; text-transform:uppercase; letter-spacing:.05em; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <aside class="w-56 bg-gray-900 min-h-screen flex flex-col flex-shrink-0">
        <div class="px-4 py-5 border-b border-gray-700">
            <p class="text-white font-extrabold text-base">🎓 EAD SENAI</p>
            <p class="text-gray-500 text-xs mt-0.5">Painel Administrativo</p>
        </div>
        <div class="px-4 py-3 border-b border-gray-700">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-senai-blue rounded-full flex items-center justify-center text-white text-xs font-bold">A</div>
                <div>
                    <p class="text-white text-xs font-semibold">Administrador</p>
                    <p class="text-gray-500 text-xs"><?php echo $dados_usuario['email']; ?></p>
                </div>
            </div>
        </div>
        <nav class="flex-1 p-3 space-y-1">
            <a href="index.php"   class="nav-link">📊 <span>Dashboard</span></a>
            <a href="cursos.php"  class="nav-link">📚 <span>Cursos</span></a>
            <a href="modulos.php" class="nav-link active">📦 <span>Módulos</span></a>
            <div class="pt-2 border-t border-gray-700 mt-2">
                <a href="../meus_cursos.php" class="nav-link">👁 <span>Ver site</span></a>
                <a href="../login.php"       class="nav-link text-red-400 hover:text-red-300">🚪 <span>Sair</span></a>
            </div>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col">
        <div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                    <a href="cursos.php" class="hover:text-senai-blue">Cursos</a>
                    <span class="text-gray-700 font-semibold"><?php echo $nome_do_curso; ?></span>
                    <span>Módulos</span>
                </div>
                <h1 class="text-xl font-extrabold text-gray-800">Módulos: <?php echo $nome_do_curso; ?></h1>
            </div>
            <a href="cursos.php" class="bg-gray-100 text-gray-600 font-bold px-4 py-2.5 rounded-lg text-sm hover:bg-gray-200 transition">← Voltar aos Cursos</a>
        </div>

        <div class="p-6 flex-1">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

                <div class="space-y-3">
                    <h2 class="font-bold text-gray-700 text-sm">Módulos Cadastrados</h2>

                    <?php 
                    // Se não tiver nenhum módulo, exibe uma mensagem
                    if (mysqli_num_rows($resultado_modulos) == 0): 
                    ?>
                        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center">
                            <p class="text-gray-500 text-sm">Nenhum módulo cadastrado neste curso.</p>
                            <p class="text-gray-400 text-xs mt-1">Use o formulário ao lado para criar o primeiro.</p>
                        </div>
                    <?php 
                    endif; 
                    
                    // Loop dos Módulos
                    while ($modulo = mysqli_fetch_assoc($resultado_modulos)): 
                        
                        // 1. Pega o ID deste módulo que está passando no loop agora
                        $id_deste_modulo = $modulo['id'];

                        // 2. Faz a contagem de aulas SÓ para este módulo
                        $sql_qtd_aulas = "SELECT COUNT(id) AS total FROM aulas WHERE modulo_id = '$id_deste_modulo'";
                        $res_qtd_aulas = mysqli_query($conexao, $sql_qtd_aulas);
                        $qtd_aulas = mysqli_fetch_assoc($res_qtd_aulas)['total'];
                    ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-senai-blue rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                <?php echo $modulo['ordem']; ?>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800"><?php echo $modulo['titulo']; ?></p>
                                
                                <p class="text-xs text-gray-400"><?php echo $qtd_aulas; ?> aulas cadastradas</p> 
                                
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between border-t border-gray-50 pt-3">
                            <div class="flex gap-2">
                                <button class="text-xs text-gray-400 hover:text-gray-600 border border-gray-200 px-2 py-1 rounded" title="Ainda não implementado">↑ Subir</button>
                                <button class="text-xs text-gray-400 hover:text-gray-600 border border-gray-200 px-2 py-1 rounded" title="Ainda não implementado">↓ Descer</button>
                            </div>
                            <div class="flex gap-1.5">
                                <a href="aulas.php?modulo_id=<?php echo $modulo['id']; ?>" class="bg-senai-blue text-white text-xs px-2.5 py-1.5 rounded-md hover:bg-senai-blue-dark transition">🎬 Aulas</a>
                                <a href="modulo_form.php?editar=<?php echo $modulo['id']; ?>" class="bg-yellow-500 text-white text-xs px-2.5 py-1.5 rounded-md hover:bg-yellow-600 transition">✏</a>
                                <a href="modulo_form.php?excluir=<?php echo $modulo['id']; ?>" onclick="return confirm('Excluir este módulo?')" class="bg-senai-red text-white text-xs px-2.5 py-1.5 rounded-md hover:bg-red-700 transition">🗑</a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-5 sticky top-6">
                    <h2 class="font-bold text-gray-700 text-sm mb-4">Adicionar Novo Módulo Rápido</h2>
                    
                    <form action="modulo_form.php" method="post">
                        
                        <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
                        
                        <input type="hidden" name="id" value="">
                        
                        <div class="mb-4">
                            <label class="form-label">Título do Módulo *</label>
                            <input type="text" name="titulo" class="form-input" placeholder="Ex: Introdução ao HTML" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Descrição (opcional)</label>
                            <textarea name="descricao" rows="3" class="form-input resize-none" placeholder="Breve descrição do módulo..."></textarea>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Ordem de Exibição</label>
                            <input type="number" name="ordem" class="form-input" value="1" min="1">
                        </div>
                        
                        <button type="submit" class="w-full bg-senai-blue text-white font-bold px-5 py-2.5 rounded-lg text-sm hover:bg-senai-blue-dark transition">
                            💾 Criar Módulo
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </main>
</body>
</html>