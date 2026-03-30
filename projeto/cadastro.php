<?php
// Iniciar a sessão e incluir a conexão
session_start();
require_once "includes/conexao.php";

$erro = "";
$sucesso = "";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recebe os dados e remove espaços em branco extras
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $senha_confirma = $_POST["senha_confirma"] ?? "";
    $cep = $_POST['cep'] ?? "";

    // Validações
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif ($senha !== $senha_confirma) {
        $erro = "As senhas não coincidem.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter no mínimo 6 caracteres.";
    } else {
        // Verifica se o e-mail já existe no banco (Prevenção de SQL Injection)
        $stmt = $conexao->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erro = "Este e-mail já está cadastrado. <a href='login.php' class='underline font-semibold'>Faça login.</a>";
        } else {
            // Criptografa a senha com hash seguro
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $tipo = 'aluno'; // Define um tipo padrão para quem se cadastra por aqui

            // Insere o usuário no banco
            $insert = $conexao->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
            $insert->bind_param("ssss", $nome, $email, $senha_hash, $tipo);
            
            if ($insert->execute()) {
                $sucesso = "Conta criada com sucesso! <a href='login.php' class='underline font-semibold'>Faça seu login.</a>";
            } else {
                $erro = "Erro do MySQL: " . $conexao->error;
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro — EAD SENAI</title>
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
<body class="bg-gray-50 min-h-screen flex flex-col">

    <nav class="bg-senai-blue shadow-md">
        <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-2 text-white font-extrabold text-lg">
                🎓 <span>EAD SENAI</span>
            </a>
            <a href="login.php" class="text-blue-200 hover:text-white text-sm transition">
                Já tem conta? <span class="underline font-semibold">Faça login</span>
            </a>
        </div>
    </nav>

    <main class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                <div class="bg-senai-green px-8 py-6 text-center">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-3xl">👤</span>
                    </div>
                    <h1 class="text-white font-extrabold text-xl">Criar sua Conta</h1>
                    <p class="text-green-100 text-sm mt-1">Cadastre-se gratuitamente e comece a aprender</p>
                </div>

                <div class="px-8 py-6">

                    <?php if (!empty($erro)): ?>
                    <div class="bg-red-50 border border-red-300 text-red-700 rounded-lg p-3 mb-4 flex items-center gap-2 text-sm">
                        <span class="font-bold">⚠</span>
                        <span><?php echo $erro; ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($sucesso)): ?>
                    <div class="bg-green-50 border border-green-300 text-green-700 rounded-lg p-3 mb-4 flex items-center gap-2 text-sm">
                        <span class="font-bold">✓</span>
                        <span><?php echo $sucesso; ?></span>
                    </div>
                    <?php endif; ?>

                    <form action="" method="POST">

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Nome Completo *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">👤</span>
                                <input
                                    name="nome"
                                    type="text"
                                    required
                                    placeholder="João da Silva"
                                    class="w-full border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-senai-green focus:border-transparent"
                                >
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">E-mail *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">✉</span>
                                <input
                                    name="email"
                                    type="email"
                                    required
                                    placeholder="joao@email.com"
                                    class="w-full border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-senai-green focus:border-transparent"
                                >
                            </div>
                        </div>

                            <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Cep *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">📍​</span>
                                <input
                                    id="cep"
                                    name="cep"
                                    type="number"
                                    required
                                    placeholder="88001300"
                                    class="w-full border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-senai-green focus:border-transparent"
                                >
                            </div>
                        </div>

                                                    <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Rua *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">-</span>
                                <input
                                    name="rua"
                                    type="text"
                                    required
                                    placeholder="Rua Imigrantes Brito"
                                    class="w-full border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-senai-green focus:border-transparent"
                                    id="rua"
                                >
                            </div>
                        </div>

                                                    <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Bairro *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">-</span>
                                <input
                                    name="bairro"
                                    type="text"
                                    required
                                    placeholder="Centro"
                                    class="w-full border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-senai-green focus:border-transparent"
                                    id="bairro"
                                >
                            </div>
                        </div>

                            <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">UF *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">-</span>
                                <input
                                    name="uf"
                                    type="text"
                                    required
                                    placeholder="SC"
                                    class="w-full border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-senai-green focus:border-transparent"
                                    id="uf"
                                >
                            </div>
                        </div>


                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Senha * <span class="text-gray-400 font-normal">(mínimo 6 caracteres)</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">🔒</span>
                                <input
                                    name="senha"
                                    type="password"
                                    required
                                    placeholder="••••••••"
                                    class="w-full border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-senai-green focus:border-transparent"
                                >
                            </div>
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

                        <button type="submit" class="w-full bg-senai-green hover:bg-green-600 text-white font-bold py-3 rounded-lg transition text-sm">
                            Criar Minha Conta
                        </button>
                    </form>

                    <div class="relative my-5">
                        <div class="border-t border-gray-200"></div>
                        <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-3 text-xs text-gray-400">ou</span>
                    </div>

                    <a href="login.php" class="block w-full border-2 border-senai-blue text-senai-blue font-bold py-2.5 rounded-lg text-sm text-center hover:bg-blue-50 transition">
                        Já tenho conta — Fazer login
                    </a>

                </div>
            </div>

            <p class="text-center text-xs text-gray-400 mt-5">
                <a href="index.php" class="hover:text-senai-blue transition">← Voltar à página inicial</a>
            </p>

        </div>

    </main>

    <footer class="bg-senai-blue text-blue-200 text-center text-xs py-3">
        SENAI — Sistema EAD &nbsp;|&nbsp; Todos os direitos reservados
    </footer>

    <script>
        const campoCep = document.getElementById("cep");
        campoCep.addEventListener("blur", function (){

        console.log("O evento blur funcionou!");

        });

    </script>
</body>
</html>