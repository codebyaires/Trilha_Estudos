<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco
require_once "includes/conexao.php";

$erro = "";

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Receber os dados
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    // Validação básica
    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
    
        // Buscar o usuário no banco
        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verificar se encontrou o usuário
        if ($usuario = $resultado->fetch_assoc()) {
            
            // Verificar a senha
            if (password_verify($senha, $usuario["senha"])) {
                
                // Sucesso! Gerar crachá (Sessão)
                session_regenerate_id(true);
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["usuario_nome"] = $usuario["nome"];
                $_SESSION["usuario_tipo"] = $usuario["tipo"];

                // Redirecionar para a área logada
                header("Location: meus_cursos.php");
                exit;
            } else {
                $erro = "Email ou senha incorretos.";
            }
        } else {
            $erro = "Email ou senha incorretos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Projeto SENAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md border-t-4 border-blue-600">

        <h1 class="text-2xl font-bold text-center text-gray-800 mb-2">Entrar no Sistema</h1>
        <p class="text-center text-gray-500 text-sm mb-6">Acesse a plataforma EAD SENAI</p>

        <?php if (!empty($erro)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-5 text-center">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold text-sm mb-1.5">Email</label>
                <input type="email" id="email" name="email" required placeholder="Digite seu email"
                    class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <div class="mb-6">
                <label for="senha" class="block text-gray-700 font-semibold text-sm mb-1.5">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="Digite sua senha"
                    class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 transition duration-200">
                Entrar na Plataforma
            </button>
            
            <p class="text-center text-sm text-gray-500 mt-5">
                Não tem uma conta? <a href="cadastro.php" class="text-blue-600 font-semibold hover:underline">Cadastre-se</a>
            </p>

        </form>
    </div>

</body>
</html>