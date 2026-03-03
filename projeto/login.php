<?php
// ============================================
// Arquivo: login.php
// Função: Tela de login e autenticação do usuário
// ============================================

// Iniciar a sessão
session_start();

// Se já está logado, redireciona para o dashboard
if (isset($_SESSION["usuario_id"])) {
    header("Location: usuario.php");
    exit;
}

// Incluir o arquivo de conexão com o banco
require_once "conexao.php";

// Variável para armazenar mensagem de erro
$erro = "";
$_SESSION["usuario_tipo"] = $usuario["tipo"];

// Verificar se o formulário foi enviado (method POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // echo '<pre>';
    // print_r($_POST);
    // die;
    // Receber os dados do formulário
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Buscar o usuário no banco pelo email
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = mysqli_query($conexao, $sql);

    // echo '<pre>';
    // print_r($resultado);
    // die;

    // Verificar se encontrou o usuário
    if ($usuario = mysqli_fetch_assoc($resultado)) {

    //     echo '<pre>';
    // print_r($usuario);
    // die;
        // Verificar se a senha está correta
        if (password_verify($senha, $usuario["senha"])) {
            // Guardar dados do usuário na sessão
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nome"] = $usuario["nome"];
            $_SESSION["usuario_email"] = $usuario["email"];

            // Redirecionar para o dashboard
            header("Location: cadastro_usuario.php");
            exit;
        } else {
            $erro = "Email ou senha incorretos.";
        }
    } else {
        $erro = "Email ou senha incorretos.";
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

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="css/style.css">
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
                    required
                    placeholder="Digite sua senha"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
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
