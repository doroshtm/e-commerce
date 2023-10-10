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
        <div id='pai'>
        <form name='cadastroProduto' method='post' action='./cadastro_produto.php' id='formLogin' enctype='multipart/form-data'>
            <div id="logo-login">
                <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
                Mascotero
            </div>
            <div class="label-input-login">
                <label for='nome'>Nome do produto</label>
                <input type='text' id='nome' name='nome' placeholder='Nome do produto' required><br>
            </div>
            <div class="label-input-login">
                <label for='descricao'>Descrição do produto</label>
                <input type='text' id='descricao' name='descricao' placeholder='Descrição do produto' required><br>
            </div>
            <div class="label-input-login">
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
            </div>
            <div class="label-input-login">
                <label for='preco'>Preço do produto</label>
                <input type='number' id='preco' name='preco' placeholder='Preço do produto' max=99.99 min=0 required step=0.01><br>
            </div>
            <div class="label-input-login">
                <label for='custo'>Custo do produto</label>
                <input type='number' id='custo' name='custo' placeholder='Custo do produto' max=99.99 min=0 required step=0.01><br>
            </div>
            <div class="label-input-login">
                <label for='icms'>ICMS (porcentagem)</label>
                <input type='number' id='icms' name='icms' max=99.99 required value=<?php echo $icms ?> step=0.01><br>
            </div>
            <div class="label-input-login">
                <label for='estoque'>Quantidade em estoque</label>
                <input type='number' id='estoque' name='estoque' placeholder='Quantidade em estoque' min=0 required><br>
            </div>
            <div class="label-input-login">
                <label for='imagem'>Imagem do produto</label>
                <input type='file' id='imagem' name='imagem' placeholder='Link da imagem do produto' maxlength=255 required accept='image/*'><br>
            </div>
            <div class="label-input-login">
                <label for='codigovisual'>Código visual do produto</label>
                <input type='text' id='codigovisual' name='codigovisual' placeholder='Código visual do produto' required maxlength=50><br>
            </div>
            <br><br>
            <input type='submit' value='Cadastrar'>
        <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (count($_POST) < 8 || !isset($_FILES['imagem'])) {
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

                if ($icms >= 100) {
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

                $gross_profit = $price - $cost;
                $connection = connect();
                $insert = $connection->prepare("insert into tbl_produto (nome, descricao, categoria, preco, custo, icms, quantidade_estoque, imagem, codigovisual, margem_lucro) VALUES (:nome, :descricao, :categoria, :preco, :custo, :icms, :estoque, :image, :codigovisual, :margem_lucro)");
                $insert->execute(array(
                    ':nome' => $name,
                    ':descricao' => $description,
                    ':categoria' => $category,
                    ':preco' => $price,
                    ':custo' => $cost,
                    ':icms' => $icms_form,
                    ':estoque' => $stock,
                    ':image' => $_FILES['imagem']['name'],
                    ':codigovisual' => $codigovisual,
                    ':margem_lucro' => $gross_profit - ($gross_profit * ($icms_form / 100))
                ));
                move_uploaded_file($_FILES['imagem']['tmp_name'], './imagens/produtos/' . $_FILES['imagem']['name']);
                header('Location: ./produtos.php');
            }

        ?>
        </form>
        </div>
    </body>
</html>