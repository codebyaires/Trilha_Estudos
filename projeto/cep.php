<?php

error_reporting(0);

if($_POST['rua']){
    echo '<pre>';
    print_r($_POST);
    die;
}
if($_POST['cep']){

    $cep = $_POST['cep'];

    if (strlen($cep) !== 8) {
        $erro = "CEP inválido!";
        echo $erro;
        die;
    }else{

        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $ch = curl_init();

        // 2. Configurar
        curl_setopt($ch, CURLOPT_URL, $url);              // URL para acessar
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   // Retornar como string
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);             // Máximo 10 segundos
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);    // Verificar SSL

        // 3. Executar a requisição
        $json     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $dados = json_decode($json, true);

        // echo '<pre>';
        // print_r($dados);
        // die;

        // 4. Fechar conexão
        // curl_close($ch);
        // $resposta = file_get_contents("https://viacep.com.br/ws/".$cep."/json/");
        // $dados = json_decode($resposta, true);

        // if ($dados === false) {
        //     $erro = "Falha na conexão";
        // }
        // if (isset($dados['erro'])) {
        //     $erro = "CEP não encontrado";
        // }
    }
    
    
}
// 5. Exibir os dados

?>

<!-- busca_cep.php — PARTE 2 (abaixo do PHP) -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Busca CEP</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; padding: 20px; }
        h1 { color: #2a9d80; }
        input { padding: 12px; font-size: 16px; width: 220px; border: 2px solid #ddd; border-radius: 8px; }
        button { padding: 12px 24px; font-size: 16px; background: #3bbfa0; color: white;
                 border: none; border-radius: 8px; cursor: pointer; }
        button:hover { background: #2a9d80; }
        .resultado { margin-top: 24px; padding: 24px; background: #f0f8f6;
                    border-radius: 12px; border-left: 4px solid #3bbfa0; }
        .erro { color: #e74c3c; font-weight: bold; margin-top: 16px; }
    </style>
</head>
<body>

    <h1>Busca de Endereço por CEP</h1>

    <?php if($_POST['cep']){ ?>
        <form method="POST" action="">
            <input type="text" name="cep" value="<?=$dados['cep']?>" placeholder="Digite o CEP" maxlength="9" required>
            <input type="text" name="rua" value="<?=$dados['logradouro']?>" placeholder="Digite a rua">
            <input type="text" name="bairro"value="<?=$dados['bairro']?>" placeholder="Digite o Bairro">
            <input type="text" name="cidade" value="<?=$dados['localidade']?>" placeholder="Digite a cidade">
            <button type="submit">Enviar</button>
        </form>
    <?php }else{ ?>
        <form method="POST" action="">
            <input type="text" name="cep" placeholder="Digite o CEP" maxlength="9" required>
            <button type="submit">Buscar</button>
        </form>
    <?php } ?>