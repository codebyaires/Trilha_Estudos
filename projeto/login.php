<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco
require_once "includes/conexao.php";

$erro = "";

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Receber os dados com segurança
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $senha_confirma = $_POST["senha_confirma"] ?? "";

        // Validações
    if (empty($email) || empty($senha) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif ($senha !== $senha_confirma) {
        $erro = "As senhas não coincidem.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter no mínimo 6 caracteres.";
    } else {
    
        // Buscar o usuário no banco usando Prepared Statements (Seguro)
        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verificar se encontrou o usuário
        if ($usuario = $resultado->fetch_assoc()) {
            
            // Verificar se a senha bate com a criptografia do banco
            if (password_verify($senha, $usuario["senha"])) {
                
                // Regenerar ID da sessão por segurança
                session_regenerate_id(true);

                // Guardar dados do usuário na sessão
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["usuario_nome"] = $usuario["nome"];
                $_SESSION["usuario_email"] = $usuario["email"];
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

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">

        <!-- Título -->
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Entrar no Sistema
        </h1>

        <!-- Mensagem de erro -->
        <?php if (!empty($erro)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de Login -->
        <form method="POST" action="login.php">

            <!-- Campo Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">
                    Email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    autocomplete="username"
                    required
                    placeholder="Digite seu email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Campo Senha -->
            <div class="mb-6">
                <label for="senha" class="block text-gray-700 font-medium mb-2">
                    Senha
                </label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    autocomplete="current-password"
                    required
                    placeholder="Digite sua senha"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

                                    <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Confirmar Senha *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">🔒</span>
                                <input
                                    name="senha_confirma"
                                    type="password"
                                    required
                                    placeholder="Repita a senha"
                                    class="w-full border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-senai-green focus:border-transparent"
                                >
                            </div>
                        </div>

            <!-- Botão Entrar -->
            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700 transition duration-200"
            >
                Entrar
            </button>

        </form>

    </div>

</body>
</html>
