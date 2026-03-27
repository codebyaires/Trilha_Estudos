<?php
session_start();
require_once "conexao.php";
require_once "includes/menu.php";

$sucesso = "";
$erro = "";
$editando = NULL;
if (isset($_GET["editar"])) {
    $id = $_GET["editar"];
    $sql = "SELECT * FROM usuarios WHERE id = '$id'";
    $res = mysqli_query($conexao, $sql);
    $editando = mysqli_fetch_assoc($res);
}
if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];
    $sql = "DELETE FROM usuarios WHERE id = '$id'";
    $res = mysqli_query($conexao, $sql);
}


// Verificar se o formulário de cadastro foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id  = $_POST["id"];
    $nome  = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $tipo = 'admin';
  
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = mysqli_query($conexao, $sql);
     
    if (mysqli_num_rows($resultado) > 0 && !$editando) {
        $erro = "Este email já está cadastrado.";
    } else {
        if($id){
            $sql = "UPDATE usuarios SET 
            nome = '$nome',
            email = '$email'
            WHERE id = $id
            ";
    
            $sucesso = "Usuário atualizado com sucesso!";
        }else{
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
            ('$nome', '$email', '$senhaHash', '$tipo')";
            $sucesso = "Usuário cadastrado com sucesso!";
        }

        if (!mysqli_query($conexao, $sql)) {
            $erro = "Erro ao cadastrar usuário.";
        }
        
    }
}
 
    


// Buscar todos os cliente para listar

?>


    <main class="flex-1 flex flex-col">
          <!-- Mensagem de sucesso -->
        <?php if (!empty($sucesso)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?php echo $sucesso; ?>
            </div>
        <?php endif; ?>

        <!-- Mensagem de erro -->
        <?php if (!empty($erro)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>
        <div class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                <a href="cursos.html" class="hover:text-senai-blue">Cursos</a> ›
                <a href="modulos.html" class="hover:text-senai-blue">Módulos</a> ›
                <span class="text-gray-700 font-semibold">Editar Módulo</span>
            </div>
            <h1 class="text-xl font-extrabold text-gray-800">Editar Módulo</h1>
        </div>
        <div class="p-6 flex-1 max-w-xl">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form  method="post">
                    <input type="hidden" value="<?=$editando['id'] ?? "" ?>" name="id"/>
                    <div class="mb-4">
                        <label class="form-label">Nome *</label>
                        <input type="text" name="nome" class="form-input" required placeholder="Digite seu Nome">
                        
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Email *</label>
                        <input type="text" name="email" class="form-input" required placeholder="Digite seu E-mail" >
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Senha *</label>
                        <input type="password" name="senha" class="form-input" required placeholder="****">
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
