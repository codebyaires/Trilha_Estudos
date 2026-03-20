<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco
require_once "../includes/conexao.php";
require_once "../includes/logado_admin.php";

// Buscar todos os cursos cadastrados
$sql_cursos = "SELECT * FROM cursos ORDER BY id DESC";
$resultado_cursos = mysqli_query($conexao, $sql_cursos);

// Contar o total de cursos para o rodapé
$total_cursos = mysqli_num_rows($resultado_cursos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Cursos — Admin | EAD SENAI</title>
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

    <!-- SIDEBAR -->
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
            <a href="index.php"      class="nav-link">📊 <span>Dashboard</span></a>
            <a href="cursos.php"     class="nav-link active">📚 <span>Cursos</span></a>
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
                <h1 class="text-xl font-extrabold text-gray-800">Gerenciar Cursos</h1>
                <p class="text-sm text-gray-500">Cadastre, edite e organize os cursos da plataforma</p>
            </div>
            <a href="curso_form.php" class="bg-senai-green text-white font-bold px-4 py-2.5 rounded-lg text-sm hover:bg-green-600 transition flex items-center gap-2">
                + Novo Curso
            </a>
        </div>

        <div class="p-6 flex-1">

            <!-- MENSAGEM DE SUCESSO -->
            <div class="bg-green-50 border border-green-300 text-green-700 rounded-lg p-3 mb-5 flex items-center gap-2 text-sm">
                <span class="font-bold text-base">✓</span>
                <span>Curso excluído com sucesso!</span>
                <button class="ml-auto text-green-400 hover:text-green-700 text-lg leading-none"></button>
            </div>

            <!-- TABELA DE CURSOS -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-senai-blue text-white">
                        <tr>
                            <th class="px-4 py-3 text-left w-10">#</th>
                            <th class="px-4 py-3 text-left">Curso</th>
                            <th class="px-4 py-3 text-center">Módulos</th>
                            <th class="px-4 py-3 text-center">Aulas</th>
                            <th class="px-4 py-3 text-center">Inscrições</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Cadastrado em</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

    <?php 
    while ($curso = mysqli_fetch_assoc($resultado_cursos)): 
        $id_do_curso = $curso['id'];

        // Contar os módulos deste curso
        $sql_qtd_mod = "SELECT COUNT(id) AS total_modulos FROM modulos WHERE curso_id = '$id_do_curso'";
        $res_qtd_mod = mysqli_query($conexao, $sql_qtd_mod);
        $qtd_modulos = mysqli_fetch_assoc($res_qtd_mod)['total_modulos'];

        // Contar as aulas deste curso (usando um JOIN super eficiente)
        // Isso conta todas as aulas que estão em módulos que pertencem a este curso
        $sql_qtd_aulas = "SELECT COUNT(aulas.id) AS total_aulas 
                          FROM aulas 
                          INNER JOIN modulos ON aulas.modulo_id = modulos.id 
                          WHERE modulos.curso_id = '$id_do_curso'";
        $res_qtd_aulas = mysqli_query($conexao, $sql_qtd_aulas);
        $qtd_aulas = mysqli_fetch_assoc($res_qtd_aulas)['total_aulas'];
    ?>
    <tr class="hover:bg-gray-50 transition">
        
        <td class="px-4 py-3 text-gray-400 font-mono text-xs"><?php echo $curso['id']; ?></td>
        
        <td class="px-4 py-3">
            <div class="flex items-center gap-3">
                <?php if (!empty($curso['capa'])): ?>
                    <img src="../uploads/<?php echo $curso['capa']; ?>" alt="Capa" class="w-10 h-10 rounded-lg object-cover flex-shrink-0 border border-gray-200">
                <?php else: ?>
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-lg">🌐</span>
                    </div>
                <?php endif; ?>
                <div>
                    <p class="font-semibold text-gray-800"><?php echo $curso['titulo']; ?></p>
                    <p class="text-xs text-gray-400 mt-0.5 whitespace-nowrap overflow-hidden text-ellipsis max-w-[200px]" title="<?php echo $curso['descricao']; ?>">
                        <?php echo $curso['descricao']; ?>
                    </p>
                </div>
            </div>
        </td>

        <td class="px-4 py-3 text-center text-gray-600 font-semibold"><?php echo $qtd_modulos; ?></td>
        <td class="px-4 py-3 text-center text-gray-600 font-semibold"><?php echo $qtd_aulas; ?></td>
        
        <td class="px-4 py-3 text-center text-gray-600 font-semibold">0</td>

        <td class="px-4 py-3 text-center">
            <?php if ($curso['ativo'] == 1): ?>
                <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Ativo</span>
            <?php else: ?>
                <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2.5 py-1 rounded-full">Inativo</span>
            <?php endif; ?>
        </td>

        <td class="px-4 py-3 text-center text-xs text-gray-400">
            <?php echo date('d/m/Y', strtotime($curso['criado_em'])); ?>
        </td>

        <td class="px-4 py-3 text-center">
            <div class="flex items-center justify-center gap-1.5">
                <a href="modulos.php?curso_id=<?php echo $curso['id']; ?>" class="bg-senai-blue text-white text-xs px-2.5 py-1.5 rounded-md hover:bg-senai-blue-dark transition" title="Ver Módulos">📦 Módulos</a>
                <a href="curso_form.php?editar=<?php echo $curso['id']; ?>" class="bg-yellow-500 text-white text-xs px-2.5 py-1.5 rounded-md hover:bg-yellow-600 transition" title="Editar">✏ Editar</a>
                <a href="curso_form.php?excluir=<?php echo $curso['id']; ?>" onclick="return confirm('Excluir este curso?')" class="bg-senai-red text-white text-xs px-2.5 py-1.5 rounded-md hover:bg-red-700 transition" title="Excluir">🗑</a>
            </div>
        </td>
        
    </tr>
    <?php endwhile; ?>

</tbody>
                </table>

                <!-- RODAPÉ DA TABELA -->
                <div class="border-t border-gray-100 px-4 py-3 flex items-center justify-between bg-gray-50">
                    <p class="text-xs text-gray-400">Exibindo <?php echo $total_cursos; ?> cursos</p>
                    <div class="flex gap-1">
                        <button class="px-3 py-1 text-xs border border-gray-300 rounded bg-white text-gray-500">← Anterior</button>
                        <button class="px-3 py-1 text-xs border border-senai-blue rounded bg-senai-blue text-white font-semibold">1</button>
                        <button class="px-3 py-1 text-xs border border-gray-300 rounded bg-white text-gray-500">Próxima →</button>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
