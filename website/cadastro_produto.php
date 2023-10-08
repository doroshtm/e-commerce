<?php
    $icms = 10;
    include("util.php");
    $sessID = startSession();
    if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
        header('Location: ./');
    }
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
        <title>Cadastro de produtos | Mascotero</title>
    </head>
    <body>
        <form name='cadastroProduto' method='post' action='./cadastro_produto.php' id='formCadProduto'>
            <label for='nome'>Nome do produto</label>
            <input type='text' id='nome' name='nome' placeholder='Nome do produto' required><br>
            <label for='descricao'>Descrição do produto</label>
            <input type='text' id='descricao' name='descricao' placeholder='Descrição do produto' required><br>
            <label for='categoria'>Categoria</label>
            <select name='categoria' id='categoria' required>
                <?php
                    $connection = connect();
                    $select = $connection->prepare('select * from tbl_categoria');
                    $select->execute();
                    $result = $select->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        echo "<option value='{$row['id_categoria']}'>{$row['nome']}</option>";
                    }
                ?>
            </select><br>
            <label for='preco'>Preço do produto</label>
            <input type='number' id='preco' name='preco' placeholder='Preço do produto' max=99.99 min=0 required step=0.01><br>
            <label for='custo'>Custo do produto</label>
            <input type='number' id='custo' name='custo' placeholder='Custo do produto' max=99.99 min=0 required step=0.01><br>
            <label for='icms'>ICMS (porcentagem)</label>
            <input type='number' id='icms' name='icms' max=99.99 required value=<?php echo $icms ?> step=0.01><br>
            <label for='estoque'>Quantidade em estoque</label>
            <input type='number' id='estoque' name='estoque' placeholder='Quantidade em estoque' min=0 required><br>
            <label for='imagem'>Imagem do produto</label>
            <input type='text' id='imagem' name='imagem' placeholder='Link da imagem do produto' maxlength=255 required><br>
            <label for='codigovisual'>Código visual do produto</label>
            <input type='text' id='codigovisual' name='codigovisual' placeholder='Código visual do produto' required maxlength=50><br>
            <br><br>
            <input type='submit' value='Cadastrar'>
        </form>
        <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (count($_POST) < 9) {
                    echo "Preencha todos os campos!";
                    die();
                }
                $name = $_POST['nome'];
                $description = $_POST['descricao'];
                $category = $_POST['categoria'];
                $price = round($_POST['preco'], 2);
                $cost = round($_POST['custo'], 2);
                $icms_form = $_POST['icms'];
                $stock = $_POST['estoque'];
                $image = $_POST['imagem'];
                $codigovisual = $_POST['codigovisual'];

                if ($icms >= 100) {
                    echo "ICMS inválido!";
                    die();
                }
                if ($price >= 100 || $price < 0) {
                    echo "Preço inválido!";
                    die();
                }
                if ($cost >= 100 || $cost < 0) {
                    echo "Custo inválido!";
                    die();
                }
                if ($stock < 0) {
                    echo "Estoque inválido!";
                    die();
                }
                if (strlen($image) > 255) {
                    echo "Link da imagem inválido!";
                    die();
                }
                if (strlen($codigovisual) > 50) {
                    echo "Código visual inválido!";
                    die();
                }
                if ($category < 1 || $category > 4) {
                    echo "Categoria inválida!";
                    die();
                }

                $gross_profit = $price - $cost;
                $connection = connect();
                $insert = $connection->prepare('insert into tbl_produto (nome, descricao, categoria, preco, custo, icms, quantidade_estoque, imagem, codigovisual, margem_lucro) VALUES (:nome, :descricao, :categoria, :preco, :custo, :icms, :estoque, :imagem, :codigovisual, :margem_lucro)');
                $insert->execute(array(
                    ':nome' => $name,
                    ':descricao' => $description,
                    ':categoria' => $category,
                    ':preco' => $price,
                    ':custo' => $cost,
                    ':icms' => $icms_form,
                    ':estoque' => $stock,
                    ':imagem' => $image,
                    ':codigovisual' => $codigovisual,
                    ':margem_lucro' => $gross_profit - ($gross_profit * ($icms_form / 100))
                ));
                header('Location: ./produtos.php');
            }

        ?>
    </body>
</html>