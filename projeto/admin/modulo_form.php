<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco
require_once "../includes/conexao.php";
require_once "../includes/logado.php";

// Variáveis para mensagens
$sucesso = "";
$erro = "";
$editando = NULL;

if (isset($_GET["editar"])) {
    $id = $_GET["editar"];
    $sql = "SELECT * FROM modulos WHERE id = '$id'";
    $res = mysqli_query($conexao, $sql);
    $editando = mysqli_fetch_assoc($res);
}

if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];
    $sql = "DELETE FROM modulos WHERE id = '$id'";
    $res = mysqli_query($conexao, $sql);
}

// Verificar se o formulário de cadastro foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST["id"] ?? null;
    $curso_id = $_POST["curso_id"] ?? "";
    $titulo  = $_POST["titulo"] ?? "";
    $descricao  = $_POST["descricao"] ?? "";
    $ordem  = $_POST["ordem"] ?? "";

    // Verificar se o módulo já existe (para não ter títulos repetidos)
    if ($id) {
        $sql_busca = "SELECT * FROM modulos WHERE titulo = '$titulo' AND id != '$id'";
    } else {
        $sql_busca = "SELECT * FROM modulos WHERE titulo = '$titulo'";
    }
    $resultado_busca = mysqli_query($conexao, $sql_busca);

    if (mysqli_num_rows($resultado_busca) > 0) {
        $erro = "Já existe um módulo cadastrado com este título.";
    } else {
        
        // Montar a instrução SQL certa UPDATE ou INSERT
        if ($id) {
            // UPDATE na tabela modulos
            $sql_salvar = "UPDATE modulos SET 
                           curso_id = '$curso_id', 
                           titulo = '$titulo', 
                           descricao = '$descricao', 
                           ordem = '$ordem' 
                           WHERE id = '$id'";
        } else {
            $sql_salvar = "INSERT INTO modulos (curso_id, titulo, descricao, ordem) 
                           VALUES ('$curso_id', '$titulo', '$descricao', '$ordem')";
        }
        
// Executar a instrução no banco de dados
        if (mysqli_query($conexao, $sql_salvar)) {
            // REDIRECIONA PARA A TELA DE MÓDULOS LEVANDO O ID DO CURSO!
            header("Location: modulos.php?curso_id=" . $curso_id);
            exit;
        } else {
            $erro = "Erro ao salvar no banco: " . mysqli_error($conexao);
        }
    }
}

// Buscar cursos ordenando pela coluna titulo
$sql_cursos = "SELECT id, titulo FROM cursos ORDER BY titulo ASC";
$res_cursos = mysqli_query($conexao, $sql_cursos);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $editando ? 'Editar Módulo' : 'Novo Módulo'; ?> — Admin | EAD SENAI</title>
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
        <div class="px-4 py-5 border-b border-gray-700"><p class="text-white font-extrabold text-base">🎓 EAD SENAI</p><p class="text-gray-500 text-xs">Painel Administrativo</p></div>
        <nav class="flex-1 p-3 space-y-1 pt-4">
            <a href="index.php"   class="nav-link">📊 Dashboard</a>
            <a href="cursos.php"  class="nav-link">📚 Cursos</a>
            <a href="modulos.php" class="nav-link active">📦 Módulos</a>
            <a href="aulas.php"   class="nav-link">🎬 Aulas</a>
            <div class="pt-2 border-t border-gray-700 mt-2">
                <a href="../login.php" class="nav-link text-red-400">🚪 Sair</a>
            </div>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col">
        <?php if (!empty($sucesso)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded m-6 mb-0">
                <?php echo $sucesso; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($erro)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded m-6 mb-0">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white border-b border-gray-200 px-6 py-4 mt-6">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                <a href="cursos.php" class="hover:text-senai-blue">Cursos</a>
                <a href="modulos.php" class="hover:text-senai-blue">Módulos</a>
                <span class="text-gray-700 font-semibold"><?php echo $editando ? 'Editar Módulo' : 'Novo Módulo'; ?></span>
            </div>
            <h1 class="text-xl font-extrabold text-gray-800"><?php echo $editando ? 'Editar Módulo' : 'Novo Módulo'; ?></h1>
        </div>

        <div class="p-6 flex-1 max-w-xl">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?php echo $editando['id'] ?? ''; ?>" />

                    <div class="mb-4">
                        <label class="form-label">Curso Vinculado *</label>
                        <select name="curso_id" class="form-input" required>
                            <option value="">Selecione um curso...</option>
                            <?php 
                            while ($curso = mysqli_fetch_assoc($res_cursos)): 
                                $selecionado = ($editando && $editando['curso_id'] == $curso['id']) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $curso['id']; ?>" <?php echo $selecionado; ?>>
                                    <?php echo $curso['titulo']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Título do Módulo *</label>
                        <input type="text" name="titulo" value="<?php echo $editando['titulo'] ?? ''; ?>" class="form-input" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Descrição (opcional)</label>
                        <textarea name="descricao" rows="4" class="form-input resize-none"><?php echo $editando['descricao'] ?? ''; ?></textarea>
                    </div>
                    
                    <div class="mb-5">
                        <label class="form-label">Ordem de Exibição</label>
                        <input type="number" name="ordem" value="<?php echo $editando['ordem'] ?? ''; ?>" class="form-input">
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="bg-senai-blue text-white font-bold px-5 py-2.5 rounded-lg text-sm hover:bg-senai-blue-dark transition">💾 Salvar</button>
                        <a href="modulos.php" class="bg-gray-100 text-gray-600 font-semibold px-5 py-2.5 rounded-lg text-sm hover:bg-gray-200 transition">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>