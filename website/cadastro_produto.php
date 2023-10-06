<?php
    include("util.php");
    $sessID = startSession();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles_header_footer.css">
    <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de produtos</title>
</head>
<body>
    <?php
        if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
            header('Location: ./');
        }
    ?>
    <form name='cadastroProduto' method='post' action='./cadastro_produto.php' id='formCadProduto'>
        <input type='text' name='nome' placeholder='Nome do produto' required><br>
        <input type='text' name='descricao' placeholder='Descrição do produto' required><br>
        <label for='categoria'>Categoria</label>
        <select name='categoria' id='categoria' required>
            <?php
                $connection = connect();
                $select = $connection->prepare('select * from tbl_categoria');
                $select->execute();
                $result = $select->fetch(PDO::FETCH_ASSOC);
                while ($result != NULL) {
                    echo "<option value='{$result['id_categoria']}'>{$result['nome']}</option>";
                    $result = $select->fetch(PDO::FETCH_ASSOC);
                }
            ?>
        </select><br>
        <input type='number' name='preco' placeholder='Preço do produto' max=99.99 min=0 required><br>
        <input type='number' name='custo' placeholder='Custo do produto' max=99.99 min=0 required><br>
        <label for='icms'>ICMS</label>
        <input type='number' name='icms' max=99.99 required disabled value=10><br>
        <input type='number' name='estoque' placeholder='Quantidade em estoque' min=0 required><br>
        <input type='text' name='imagem' placeholder='Link da imagem do produto' maxlength=255 required><br>
        <input type='text' name='codigovisual' placeholder='Código visual do produto' required>
        <br><br>
        <input type='submit' value='Cadastrar'>
    </form>
    <?php
        if(isset($_POST['nome'])) {
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $categoria = $_POST['categoria'];
            $preco = $_POST['preco'];
            $custo = $_POST['custo'];
            $icms = $_POST['icms'];
            $estoque = $_POST['estoque'];
            $imagem = $_POST['imagem'];
            $codigovisual = $_POST['codigovisual'];

            $connection = connect();
            $insert = $connection->prepare('insert into tbl_produto (nome, descricao, id_categoria, preco, custo, icms, estoque, imagem, codigovisual) values (:nome, :descricao, :categoria, :preco, :custo, :icms, :estoque, :imagem, :codigovisual)');
            $insert->execute(array(
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':categoria' => $categoria,
                ':preco' => $preco,
                ':custo' => $custo,
                ':icms' => $icms,
                ':estoque' => $estoque,
                ':imagem' => $imagem,
                ':codigovisual' => $codigovisual
            ));
            header('Location: ./');
        }

    ?>
</body>