<?php
    include("util.php");
    startSession();
    if(!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
        header('Location: ./');
    }
    $connection = connect();
    $select_product = $connection->prepare('select * from tbl_produto where id_produto = ' . $_GET['id']);
    $select_product->execute();
    $result = $select_product->fetch(PDO::FETCH_ASSOC);
    
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles_header_footer.css">
        <link rel="stylesheet" href="styles_paginas.css">
        <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
        <title>Alterar produto | Mascotero</title>
    </head>
    <body>
        <form name='alterarProduto' method='post' action='./alteracao_produto.php?id=<?php echo $_GET['id'] ?>' id='formAltProduto'>
            <label for='nome'>Nome do produto</label>
            <input type='text' id='nome' name='nome' placeholder='Nome do produto' required value=<?php echo $result['nome'] ?>><br>
            <label for='descricao'>Descrição do produto</label>
            <input type='text' id='descricao' name='descricao' placeholder='Descrição do produto' required value=<?php echo $result['descricao'] ?>><br>
            <label for='categoria'>Categoria</label>
            <select name='categoria' id='categoria' required>
                <?php
                    $select = $connection->prepare('select * from tbl_categoria');
                    $select->execute();
                    $result_category = $select->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result_category as $row) {
                        $selected = $result['categoria'] == $row['id_categoria'] ? 'selected' : '';
                        echo "<option  " . $selected . " value='{$row['id_categoria']}'>{$row['nome']}</option>";
                    }
                ?>
            </select><br>
            <label for='preco'>Preço do produto</label>
            <input type='number' id='preco' name='preco' placeholder='Preço do produto' max=99.99 min=0 required value=<?php echo $result['preco'] ?> step=0.01><br>
            <label for='custo'>Custo do produto</label>
            <input type='number' id='custo' name='custo' placeholder='Custo do produto' max=99.99 min=0 required value=<?php echo $result['custo'] ?> step=0.01><br>
            <label for='icms'>ICMS (porcentagem)</label>
            <input type='number' id='icms' name='icms' max=99.99 required value=<?php echo $result['icms'] ?> step=0.01><br>
            <label for='estoque'>Quantidade em estoque</label>
            <input type='number' id='estoque' name='estoque' placeholder='Quantidade em estoque' min=0 required value=<?php echo $result['quantidade_estoque'] ?>><br>
            <label for='imagem'>Imagem do produto</label>
            <input type='text' id='imagem' name='imagem' placeholder='Link da imagem do produto' maxlength=255 required value=<?php echo $result['imagem'] ?>><br>
            <label for='codigovisual'>Código visual do produto</label>
            <input type='text' id='codigovisual' name='codigovisual' placeholder='Código visual do produto' required maxlength=50 value=<?php echo $result['codigovisual'] ?>><br>
            <br><br>
            <input type='submit' value='Alterar'>
    </body>
    <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['nome'];
            $description = $_POST['descricao'];
            $category = $_POST['categoria'];
            $price = $_POST['preco'];
            $cost = $_POST['custo'];
            $icms = $_POST['icms'];
            $stock = $_POST['estoque'];
            $image = $_POST['imagem'];
            $codigovisual = $_POST['codigovisual'];
            $update = $connection->prepare("update tbl_produto set nome = '{$name}', descricao = '{$description}', categoria = {$category}, preco = {$price}, custo = {$cost}, icms = {$icms}, quantidade_estoque = {$stock}, imagem = '{$image}', codigovisual = '{$codigovisual}' where id_produto = {$_GET['id']}");
            $update->execute();
            header('Location: ./produtos.php');
        }
    ?>
</html>