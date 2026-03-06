<?php
session_start();
require_once "includes/conexao.php";
require_once "includes/menu.php";

$sucesso = "";
$erro = "";
$editando = NULL;

// 1. EXCLUIR USUÁRIO (Usando Prepared Statements)
if (isset($_GET["excluir"])) {
    $id_excluir = $_GET["excluir"];
    $stmt_del = $conexao->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt_del->bind_param("i", $id_excluir);
    if ($stmt_del->execute()) {
        // Redireciona para evitar que o usuário exclua de novo ao atualizar a página (F5)
        header("Location: usuario_form.php"); 
        exit;
    }
}

// 2. BUSCAR DADOS PARA EDITAR
if (isset($_GET["editar"])) {
    $id_editar = $_GET["editar"];
    $stmt_edit = $conexao->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt_edit->bind_param("i", $id_editar);
    $stmt_edit->execute();
    $res = $stmt_edit->get_result();
    $editando = $res->fetch_assoc();
}

// 3. SALVAR OU ATUALIZAR (Processar Formulário)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Usando ?? para evitar o erro "Undefined array key"
    $id    = $_POST["id"] ?? null;
    $nome  = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $tipo  = 'admin';
  
    // Verifica se o email já existe, MAS ignora se for o email do próprio usuário sendo editado
    $stmt_check = $conexao->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
    $id_param = $id ? $id : 0; 
    $stmt_check->bind_param("si", $email, $id_param);
    $stmt_check->execute();
    $stmt_check->store_result();
     
    if ($stmt_check->num_rows > 0) {
        $erro = "Este email já está cadastrado em outra conta.";
    } else {
        if (!empty($id)) {
            // == É UMA EDIÇÃO (UPDATE) ==
            if (!empty($senha)) {
                // Se digitou uma senha nova, atualiza a senha também
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt_upd = $conexao->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
                $stmt_upd->bind_param("sssi", $nome, $email, $senhaHash, $id);
            } else {
                // Se deixou a senha em branco, atualiza só nome e email (mantém a senha antiga)
                $stmt_upd = $conexao->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
                $stmt_upd->bind_param("ssi", $nome, $email, $id);
            }
            
            if ($stmt_upd->execute()) {
                $sucesso = "Usuário atualizado com sucesso!";
                // Atualiza os dados na tela
                $editando['nome'] = $nome;
                $editando['email'] = $email;
            } else {
                $erro = "Erro ao atualizar usuário.";
            }

        } else {
            // == É UM NOVO CADASTRO (INSERT) ==
            if (empty($senha)) {
                $erro = "A senha é obrigatória para novos cadastros.";
            } else {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt_ins = $conexao->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
                $stmt_ins->bind_param("ssss", $nome, $email, $senhaHash, $tipo);
                
                if ($stmt_ins->execute()) {
                    $sucesso = "Usuário cadastrado com sucesso!";
                } else {
                    $erro = "Erro ao cadastrar usuário.";
                }
            }
        }
    }
}
?>

    <main class="flex-1 flex flex-col">
          <?php if (!empty($sucesso)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?php echo $sucesso; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($erro)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>
        
        <div class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                <a href="cursos.html" class="hover:text-senai-blue">Cursos</a>
                <a href="modulos.html" class="hover:text-senai-blue">Módulos</a> 
                <span class="text-gray-700 font-semibold"><?php echo $editando ? 'Editar Usuário' : 'Novo Usuário'; ?></span>
            </div>
            <h1 class="text-xl font-extrabold text-gray-800"><?php echo $editando ? 'Editar Usuário' : 'Novo Usuário'; ?></h1>
        </div>

        <div class="p-6 flex-1 max-w-xl">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="post" action="">
                    <input type="hidden" value="<?php echo $editando['id'] ?? ''; ?>" name="id"/>
                    
                    <div class="mb-4">
                        <label class="form-label">Nome *</label>
                        <input type="text" name="nome" value="<?php echo $editando['nome'] ?? ''; ?>" class="form-input" required placeholder="Digite seu Nome">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" value="<?php echo $editando['email'] ?? ''; ?>" class="form-input" required placeholder="Digite seu E-mail" >
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Senha <?php echo $editando ? '(Deixe em branco para não alterar)' : '*'; ?></label>
                        <input type="password" name="senha" class="form-input" <?php echo $editando ? '' : 'required'; ?> placeholder="****">
                    </div>
                  
                    <div class="flex gap-2">
                        <button type="submit" class="bg-senai-blue text-white font-bold px-5 py-2.5 rounded-lg text-sm hover:bg-senai-blue-dark transition">💾 Salvar</button>
                        <a href="modulos.html" class="bg-gray-100 text-gray-600 font-semibold px-5 py-2.5 rounded-lg text-sm hover:bg-gray-200 transition">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>