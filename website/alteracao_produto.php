<?php
    include("util.php");
    startSession();
    if(!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
        header('Location: ./');
    }
    $id = $_GET['id'];
    $connection = connect();
    $select_product = $connection->prepare('select * from tbl_produto where id_produto = ' . $id);
    $select_product->execute();
    $result = $select_product->fetch(PDO::FETCH_ASSOC);
    if($result == NULL) {
        echo "Produto não encontrado! Redirecionando para a página de produtos...";
        header('Refresh: 3; url=./produtos.php');
        die();
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <link rel="stylesheet" href="styles_header_footer.css">
        <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&family=Montserrat&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,600&display=swap" rel="stylesheet">
        <title>Alteração de produtos | Mascotero</title>
        <title>Alterar produto | <?php echo $id ?> | Mascotero</title>
    </head>
    <body>
        <div id="pai">
            <form method="get" id="formDelProduto" action="./remocao_produto.php"></form>
            <form name="alterarProduto" method="post" action="./alteracao_produto.php?id=<?php echo $id ?>" id="formlogin" style="width:60%;" enctype="multipart/form-data">
                <div id="logo-login">
                        <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
                        Mascotero
                    </div>
                    <div id="container-grid-login">
                    <div class="label-input-login">
                        <label for="nome">Nome do produto</label>
                        <input type="text" id="nome" name="nome" placeholder="Nome do produto" required value="<?php echo $result['nome'] ?>"><br>
                    </div>
                    <div class="label-input-login">
                        <label for="descricao">Descrição do produto</label>
                        <input type="text" id="descricao" name="descricao" placeholder="Descrição do produto" required value="<?php echo $result['descricao'] ?>"><br>
                    </div>
                    <div class="label-input-login">
                        <label for="categoria">Categoria</label>
                        <select name="categoria" id="categoria" required>
                            <?php
                                $select = $connection->prepare("select * from tbl_categoria");
                                $select->execute();
                                $result_category = $select->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result_category as $row) {
                                    $selected = $result['categoria'] == $row['id_categoria'] ? 'selected' : '';
                                    echo "<option  " . $selected . " value='{$row['id_categoria']}'>{$row['nome']}</option>";
                                }
                            ?>
                    </select><br>
                    </div>
                    <div class="label-input-login">
                        <label for="estoque">Quantidade em estoque</label>
                        <input type="number" id="estoque" name="estoque" placeholder="Quantidade em estoque" min=0 required value=<?php echo $result['quantidade_estoque'] ?>><br>
                    </div>
                    <div class="label-input-login">
                        <label for="preco">Preço do produto</label>
                        <input type="number" id="preco" name="preco" placeholder="Preço do produto" max=99.99 min=0 required value=<?php echo $result['preco'] ?> step=0.01><br>
                    </div>
                    <div class="label-input-login">
                        <label for="codigovisual">Código visual do produto</label>
                        <input type="text" id="codigovisual" name="codigovisual" placeholder="Código visual do produto" required maxlength=50 value="<?php echo $result['codigovisual'] ?>"><br>
                    </div>
                    <div class="label-input-login">
                        <label for="custo">Custo do produto</label>
                        <input type="number" id="custo" name="custo" placeholder="Custo do produto" max=99.99 min=0 required value=<?php echo $result['custo'] ?> step=0.01><br>
                    </div>
                    <div class="label-input-login">
                        <label for="imagem">Margem de lucro</label>
                        <input type="number" readonly placeholder="Margem de lucro" value=<?php echo $result['margem_lucro'] ?>><br>
                    </div>
                    <div class="label-input-login">
                        <label for="icms">ICMS (porcentagem)</label>
                        <input type="number" id="icms" name="icms" max=99.99 required value=<?php echo $result['icms'] ?> step=0.01><br>
                    </div>
                    <div class="label-input-login">
                        <label for="imagem">Imagem do produto</label>
                        <input type="file" id="imagem" name="imagem" placeholder="Imagem do produto"><br>
                    </div>
                            </div>
                    <input type="submit" value="Alterar">
                    <input type="hidden" name="id" value=<?php echo $id ?> form="formDelProduto">
                    <input type="hidden" name="action" value=<?php echo $result['excluido'] ? 'Restaurar' : 'Deletar' ?> form="formDelProduto">
                    <input type="submit" value=<?php echo $result['excluido'] ? 'Restaurar' : 'Excluir' ?> form="formDelProduto">
                    <input type="button" value="Cancelar" onclick="window.history.back()">
                    <?php
                        if($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if(empty($_POST['nome']) || empty($_POST['descricao']) || empty($_POST['categoria']) || empty($_POST['estoque']) || empty($_POST['preco']) || empty($_POST['codigovisual']) || empty($_POST['custo']) || empty($_POST['icms'])) {
                                echo "<script>alert('Preencha todos os campos obrigatórios!')</script>";
                                echo "<div class='mensagem-erro'>Preencha todos os campos obrigatórios!</div>";
                                die();
                            }
                            
                            $name = $_POST['nome'];
                            $description = $_POST['descricao'];
                            $category = $_POST['categoria'];
                            $price = round($_POST['preco'], 2);
                            $cost = round($_POST['custo'], 2);
                            $icms_form = $_POST['icms'];
                            $stock = $_POST['estoque'];
                            isset($_FILES['imagem']) ? $image = $_FILES['imagem'] : '';
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
                            if (isset($image) && !preg_match("/image/", $image['type'])) {
                                echo "<script>alert('Imagem inválida!')</script>";
                                echo "<div class='mensagem-erro'>Imagem inválida!</div>";
                                die();
                            }
                            if(isset($image) && $image['size'] > 3000000) {
                                echo "<script>alert('Imagem muito grande!')</script>";
                                echo "<div class='mensagem-erro'>Imagem muito grande!</div>";
                                die();
                            }
                            $codigovisual = $_POST['codigovisual'];
                            $grossprofit = $_POST['preco'] - $_POST['custo'];
                            $update = $connection->prepare("UPDATE tbl_produto SET nome = :name, descricao = :description, categoria = :category, preco = :price, custo = :cost, icms = :icms, quantidade_estoque = :stock, " . (isset($image) ? "imagem = '{$image['name']}', " : '') . "codigovisual = :codigovisual, margem_lucro = :profit_margin WHERE id_produto = '{$id}'");
                            $update->execute(array(
                                ':name' => $name,
                                ':description' => $description,
                                ':category' => $category,
                                ':price' => $price,
                                ':cost' => $cost,
                                ':icms' => $icms_form,
                                ':stock' => $_POST['estoque'],
                                ':codigovisual' => $_POST['codigovisual'],
                                ':profit_margin' => round($grossprofit - ($grossprofit * ($_POST['icms'] / 100)), 2)
                            ));
                            move_uploaded_file($_FILES['imagem']['tmp_name'], './imagens/produtos/' . $image['name']);
                            header('Location: ./produtos.php');
                        }
                    ?>
            </form>
        </div>
    </body>
</html>