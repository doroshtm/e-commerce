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
        echo "Produto nÃ£o encontrado! Redirecionando para a pÃ¡gina de produtos...";
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
        <div id='pai'>
            <form method='get' id='formDelProduto' action='./remocao_produto.php'></form>
            <form name='alterarProduto' method='post' action='./alteracao_produto.php?id=<?php echo $id ?>' id='formlogin' style="width:60%;">
                <div id="logo-login">
                        <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
                        Mascotero
                    </div>
                    <div id="container-grid-login">
                    <div class="label-input-login">
                        <label for='nome'>Nome do produto</label>
                        <input type='text' id='nome' name='nome' placeholder='Nome do produto' required value='<?php echo $result['nome'] ?>'><br>
                    </div>
                    <div class="label-input-login">
                        <label for='descricao'>Descrição do produto</label>
                        <input type='text' id='descricao' name='descricao' placeholder='Descrição do produto' required value='<?php echo $result['descricao'] ?>'><br>
                    </div>
                    <div class="label-input-login">
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
                    </div>
                    <div class="label-input-login">
                        <label for='preco'>Preço do produto</label>
                        <input type='number' id='preco' name='preco' placeholder='Preço do produto' max=99.99 min=0 required value=<?php echo $result['preco'] ?> step=0.01><br>
                    </div>
                    <div class="label-input-login">
                        <label for='custo'>Custo do produto</label>
                        <input type='number' id='custo' name='custo' placeholder='Custo do produto' max=99.99 min=0 required value=<?php echo $result['custo'] ?> step=0.01><br>
                    </div>
                    <div class="label-input-login">
                        <label for='icms'>ICMS (porcentagem)</label>
                        <input type='number' id='icms' name='icms' max=99.99 required value=<?php echo $result['icms'] ?> step=0.01><br>
                    </div>
                    <div class="label-input-login">
                        <label for='estoque'>Quantidade em estoque</label>
                        <input type='number' id='estoque' name='estoque' placeholder='Quantidade em estoque' min=0 required value=<?php echo $result['quantidade_estoque'] ?>><br>
                    </div>
                    <div class="label-input-login">
                        <label for='imagem'>Margem de lucro</label>
                        <input type='number' readonly placeholder='Margem de lucro' value=<?php echo $result['margem_lucro'] ?>><br>
                    </div>
                    <div class="label-input-login">
                        <label for='codigovisual'>Código visual do produto</label>
                        <input type='text' id='codigovisual' name='codigovisual' placeholder='Código visual do produto' required maxlength=50 value='<?php echo $result['codigovisual'] ?>'><br>
                    </div>
                    <div class="label-input-login">
                        <label for='imagem'>Imagem do produto</label>
                        <input type='file' id='imagem' name='imagem' placeholder='Imagem do produto'><br>
                    </div>
                            </div>
                    <input type='submit' value='Alterar'>
                    <input type='hidden' name='id' value=<?php echo $id ?> form='formDelProduto'>
                    <input type='hidden' name='action' value=<?php echo $result['excluido'] ? 'Restaurar' : 'Deletar' ?> form='formDelProduto'>
                    <input type='submit' value=<?php echo $result['excluido'] ? 'Restaurar' : 'Excluir' ?> form='formDelProduto'>
                    <input type='button' value='Cancelar' onclick='window.history.back()'>
            </form>
        </div>
    </body>
    <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            isset($_FILES['imagem']) ? $image = $_FILES['imagem'] : '';
            $codigovisual = $_POST['codigovisual'];
            $update = $connection->prepare("update tbl_produto set nome = :name, descricao = :description, categoria = :category, preco = :price, custo = :cost, icms = :icms, quantidade_estoque = :stock, " . (isset($image) ? "imagem = '{$image}', " : '') . "codigovisual = :codigovisual, margem_lucro = :margem_lucro where id_produto = '{$id}'");
            $update->execute(array(
                ':name' => $_POST['nome'],
                ':description' => $_POST['descricao'],
                ':category' => $_POST['categoria'],
                ':price' => $_POST['preco'],
                ':cost' => $_POST['custo'],
                ':icms' => $_POST['icms'],
                ':stock' => $_POST['estoque'],
                ':codigovisual' => $_POST['codigovisual'],
                ':margem_lucro' => round(($_POST['preco'] - $_POST['custo'])/$_POST['icms'], 2)
            ));
            header('Location: ./produtos.php');
        }
    ?>
</html>