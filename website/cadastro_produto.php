<?php
    $standardICMS = 10;
    include("util.php");
    startSession(NULL);
    if (!isset($_SESSION['user']['isAdmin']) || !$_SESSION['user']['isAdmin']) {
        header("Location: ./");
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles_header_footer.css">
        <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&family=Montserrat&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400;1,9..40,500&display=swap" rel="stylesheet">         
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,600&display=swap" rel="stylesheet">
        <title>Cadastro de produtos | Mascotero</title>
    </head>
    <body>
        <div id="pai">
        <form name="cadastroProduto" method="post" action="./cadastro_produto.php" id="formlogin" enctype="multipart/form-data" style="width: 60%;">
            <div id="logo-login">
                <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
                Mascotero
            </div>
            <div id="container-grid-login">
            <div class="label-input-login">
                <label for="nome">Nome do produto</label>
                <input type="text" id="nome" name="nome" placeholder="Nome do produto" required><br>
            </div>
            <div class="label-input-login">
                <label for="descricao-prod">Descrição do produto</label>
                <input type="text" id="descricao-prod" name="descricao" placeholder="Descrição do produto" required><br>
            </div>
            <div class="label-input-login">
                <label for="categoria">Categoria</label>
                <select name="categoria" id="categoria" required>
                <?php
                    $connection = connect();
                    $select = $connection->prepare("select * from tbl_categoria");
                    $select->execute();
                    $result = $select->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        echo "<option value='{$row['id_categoria']}'>{$row['nome']}</option>";
                    }
                ?>
            </select><br>
            </div>
            <div class="label-input-login">
                <label for="estoque">Quantidade em estoque</label>
                <input type="number" id="estoque" name="estoque" placeholder="Quantidade em estoque" min=0 required><br>
            </div>
            <div class="label-input-login">
                <label for="preco">Preço do produto</label>
                <input type="number" id="preco" name="preco" placeholder="Preço do produto" max=99.99 min=0 required step=0.01><br>
            </div>
            <div class="label-input-login">
                <label for="codigovisual">Código visual do produto</label>
                <input type="text" id="codigovisual" name="codigovisual" placeholder="Código visual do produto" required maxlength=50><br>
            </div>
            <div class="label-input-login">
                <label for="custo">Custo do produto</label>
                <input type="number" id="custo" name="custo" placeholder="Custo do produto" max=99.99 min=0 required step=0.01><br>
            </div>
            <div class="label-input-login">
                <label for="imagem">Imagem do produto</label>
                <input type="file" id="imagem" name="imagem" placeholder="Link da imagem do produto" maxlength=255 required accept="image/*"><br>
            </div>
            <div class="label-input-login">
                <label for="icms">ICMS (porcentagem)</label>
                <input type="number" id="icms" name="icms" max=99.99 required value=<?php echo $standardICMS ?> step=0.01><br>
            </div>
            
                </div>
            <input type="submit" value="Cadastrar">
            <input type="button" value="Cancelar" onclick="window.history.back()">
        <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (empty($_POST['nome']) || empty($_POST['descricao']) || empty($_POST['categoria']) || empty($_POST['preco']) || (empty($_POST['custo']) && $_POST['custo'] != 0)
                 || empty($_POST['icms']) || (empty($_POST['estoque']) && $_POST['estoque'] != 0)
                 || empty($_POST['codigovisual']) || empty($_FILES['imagem']['name'])) {
                    echo "<script>alert('Preencha todos os campos!')</script>";
                    echo "<div class='mensagem-erro'>Preencha todos os campos!</div>";
                    die();
                }
                $name = $_POST['nome'];
                $description = $_POST['descricao'];
                $category = $_POST['categoria'];
                $price = round($_POST['preco'], 2);
                $cost = round($_POST['custo'], 2);
                $icms_form = $_POST['icms'];
                $stock = $_POST['estoque'];
                $image = $_FILES['imagem'];
                $codigovisual = $_POST['codigovisual'];

                if ($icms_form >= 100) {
                    echo "<script>alert('ICMS inválido!')</script>";
                    echo "<div class='mensagem-erro'>ICMS inválido!</div>";
                    die();
                }
                if ($price >= 100 || $price < 0) {
                    echo "<script>alert('Preço inválido!')</script>";
                    echo "<div class='mensagem-erro'>Preço inválido!</div>";
                    die();
                }
                if ($cost >= 100 || $cost < 0) {
                    echo "<script>alert('Custo inválido!')</script>";
                    echo "<div class='mensagem-erro'>Custo inválido!</div>";
                    die();
                }
                if ($stock < 0) {
                    echo "<script>alert('Estoque inválido!')</script>";
                    echo "<div class='mensagem-erro'>Estoque inválido!</div>";
                    die();
                }
                if (strlen($codigovisual) > 50) {
                    echo "<script>alert('Código visual inválido!')</script>";
                    echo "<div class='mensagem-erro'>Código visual inválido!</div>";
                    die();
                }
                if ($category < 1 || $category > 4) {
                    echo "<script>alert('Categoria inválida!')</script>";
                    echo "<div class='mensagem-erro'>Categoria inválida!</div>";
                    die();
                }
                if (!preg_match("/image/", $image['type'])) {
                    echo "<script>alert('Imagem inválida!')</script>";
                    echo "<div class='mensagem-erro'>Imagem inválida!</div>";
                    die();
                }
                if($image['size'] > 3000000) {
                    echo "<script>alert('Imagem muito grande!')</script>";
                    echo "<div class='mensagem-erro'>Imagem muito grande!</div>";
                    die();
                }

                $grossprofit = $price - $cost;
                $connection = connect();
                $insert = $connection->prepare("INSERT INTO tbl_produto (nome, descricao, categoria, preco, custo, icms, quantidade_estoque, imagem, codigovisual, margem_lucro) VALUES (:name, :description, :category, :price, :cost, :icms, :stock, :image, :codigovisual, :profit_margin)");
                $insert->execute(array(
                    ':name' => $name,
                    ':description' => $description,
                    ':category' => $category,
                    ':price' => $price,
                    ':cost' => $cost,
                    ':icms' => $icms_form,
                    ':stock' => $_POST['estoque'],
                    ':codigovisual' => $_POST['codigovisual'],
                    ':profit_margin' => round($grossprofit - ($grossprofit * ($_POST['icms'] / 100)), 2),
                    ':image' => $image['name']
                ));
                move_uploaded_file($_FILES['imagem']['tmp_name'], './imagens/produtos/' . $image['name']);
                header("Location: ./produtos.php");
            }

        ?>
        </form>
        </div>
    </body>
</html>