<?php
session_start();
require_once "includes/conexao.php";
require_once "includes/menu.php";

$sucesso = "";
$erro = "";

// LÓGICA PARA EXCLUIR O USUÁRIO
if (isset($_GET["excluir"])) {
    $id_excluir = $_GET["excluir"];
    // Usando Prepared Statement para evitar SQL Injection
    $stmt_del = $conexao->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt_del->bind_param("i", $id_excluir);
    
    if ($stmt_del->execute()) {
        $sucesso = "Usuário excluído com sucesso!";
    } else {
        $erro = "Erro ao excluir o usuário.";
    }
}
?>
    <main class="flex-1 flex flex-col">

        <div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-extrabold text-gray-800">Gerenciar Usuários</h1>
                <p class="text-sm text-gray-500">Cadastre, edite e organize os usuários do sistema</p>
            </div>
            <a href="usuario_form.php" class="bg-senai-green text-white font-bold px-4 py-2.5 rounded-lg text-sm hover:bg-green-600 transition flex items-center gap-2">
                + Novo Usuário
            </a>
        </div>

        <div class="p-6 flex-1">

            <?php if (!empty($sucesso)): ?>
                <div class="bg-green-50 border border-green-300 text-green-700 rounded-lg p-3 mb-5 flex items-center gap-2 text-sm">
                    <span class="font-bold text-base">✓</span>
                    <span><?php echo $sucesso; ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($erro)): ?>
                <div class="bg-red-50 border border-red-300 text-red-700 rounded-lg p-3 mb-5 flex items-center gap-2 text-sm">
                    <span class="font-bold text-base">⚠</span>
                    <span><?php echo $erro; ?></span>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-senai-blue text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Nome</th>
                            <th class="px-4 py-3 text-center">Email</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                    <?php 
                        // Busca os usuários no banco
                        $sql = "SELECT id, nome, email FROM usuarios ORDER BY id DESC";
                        $usuario = mysqli_query($conexao, $sql);
                        
                        // Verifica se tem usuários antes de listar
                        if (mysqli_num_rows($usuario) > 0):
                            while ($u = mysqli_fetch_assoc($usuario)): 
                    ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-left text-gray-600 font-semibold"><?=$u["nome"];?></td>
                            <td class="px-4 py-3 text-center text-gray-600 font-semibold"><?=$u["email"];?></td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="usuario_form.php?editar=<?=$u["id"]; ?>" class="bg-yellow-500 text-white text-xs px-2.5 py-1.5 rounded-md hover:bg-yellow-600 transition" title="Editar">✏ Editar</a>
                                    <a onclick="return confirm('Tem certeza que deseja excluir este usuário?')" class="bg-senai-red text-white text-xs px-2.5 py-1.5 rounded-md hover:bg-red-700 transition" href="?excluir=<?=$u["id"]; ?>">Excluir</a>
                                </div>
                            </td>
                        </tr>

                        <?php 
                            endwhile; 
                        else: 
                        ?>
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500">Nenhum usuário cadastrado.</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </main>

</body>
</html>