<?php
    include("util.php");
    startSession();
    if (!isset($_SESSION["user"]["id"])){
        echo "<script>alert('Você precisa estar logado para concluir a compra');</script>";
        header("refresh: 0; url=login.php?url=carrinho.php");
        die();
    }
    if ($_SESSION["cart"]["totalprice"]<= 0){
        echo "<script>alert('Você precisa ter itens no carrinho para concluir a compra');</script>";
        header("refresh: 0; url=produtos.php");
        die();
    }
    $connection = connect();
    $select = $connection->prepare("SELECT id_compra FROM tbl_compra WHERE usuario = :id_usuario AND status = 'AGUARDANDO PAGAMENTO'");
    $select->execute(['id_usuario' => $_SESSION["user"]["id"]]);
    $result = $select->fetch(PDO::FETCH_ASSOC);
    $select2 = $connection->prepare("SELECT id_compra FROM tbl_compra WHERE usuario = :id_usuario AND status = 'PENDENTE'");
    $select2->execute(['id_usuario' => $_SESSION["user"]["id"]]);
    $result2 = $select2->fetch(PDO::FETCH_ASSOC);
    if ($result == NULL && $result2 == NULL) {
        header("refresh: 0; url=produtos.php?message='Nenhuma compra encontrada. Por favor, tente novamente.'");
        die();
    }
    $tableCartID = $result2 != NULL ? $result2["id_compra"] : $result["id_compra"];
    if ($result != NULL && $result2 != NULL) {
        $delete = $connection->prepare("DELETE FROM tbl_compra_produto WHERE compra = :id_compra");
        $delete->execute(['id_compra' => $result["id_compra"]]);
        $delete = $connection->prepare("DELETE FROM tbl_compra WHERE id_compra = :id_compra");
        $delete->execute(['id_compra' => $result["id_compra"]]);
    }
    $select3 = $connection->prepare("SELECT produto, quantidade FROM tbl_compra_produto WHERE compra = :id_compra");
    $select3->execute(['id_compra' => $tableCartID]);
    $result3 = $select3->fetchAll(PDO::FETCH_ASSOC);
    foreach ($_SESSION["cart"] as $id => $amount) {
        if ($id == "totalprice") {
            continue;
        }
        foreach ($result3 as $row) {
            if ($row["produto"] == $id) {
                if ($row["quantidade"] != $amount) {
                    $update = $connection->prepare("UPDATE tbl_compra_produto SET quantidade = :quantidade WHERE compra = :id_compra AND produto = :produto");
                    $update->execute(['quantidade' => $amount, 'id_compra' => $tableCartID, 'produto' => $id]);
                }
            }
        }
    }
    foreach ($_SESSION["cart"] as $id => $amount) {
        if ($id == "totalprice") {
            continue;
        }
        if (!in_array($id, array_column($result3, "produto"))) {
            $insert = $connection->prepare("INSERT INTO tbl_compra_produto (compra, produto, quantidade) VALUES (:id_compra, :produto, :quantidade)");
            $insert->execute(['id_compra' => $tableCartID, 'produto' => $id, 'quantidade' => $amount]);
        }
    }
    foreach ($result3 as $row) {
        if (!array_key_exists($row["produto"], $_SESSION["cart"])) {
            $delete = $connection->prepare("DELETE FROM tbl_compra_produto WHERE compra = :id_compra AND produto = :produto");
            $delete->execute(['id_compra' => $tableCartID, 'produto' => $row["produto"]]);
        }
    }
    $update = $connection->prepare("UPDATE tbl_compra SET status = 'AGUARDANDO PAGAMENTO' WHERE id_compra = :id_compra AND status = 'PENDENTE'");
    $update->execute(['id_compra' => $tableCartID]);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $coupon = $_POST['cupom'] != "" ? $_POST['cupom'] : NULL;
        $update = $connection->prepare("UPDATE tbl_compra SET status = 'PAGO', cupom = :cupom WHERE id_compra = :id_compra");
        $update->execute(['cupom' => $coupon, 'id_compra' => $tableCartID]);
        unset($_SESSION["cart"]);
        echo "<script>alert('Compra concluída com sucesso!');</script>";
        startCartSession();
        header("refresh: 0; url=produtos.php");
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Finalizar compra | Mascotero</title>
        <link rel="stylesheet" href="styles_header_footer.css">
        <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
        <script src="js/cadastro.js?v=0.21"></script>
    </head>
    <body>
            <header id="header">
                <div class='container-logo'> 
                    <div id='imagem-logo'><img src='imagens/MC_Logo_Header.svg'> </div>
                    <a href='./' style='all:unset;'>
                        <div id='texto-logo'>Mascotero</div>
                    </a> 
                </div>
                <span id="texto-finalizar"><h2>Finalizar compra</h2></span>
            </header>
            <div id="content">
                <div class="container-geral">
            <?php
                $address = isset($_SESSION["user"]["address"]) ? $_SESSION["user"]["address"] : "Av. Nações Unidas, 58-50 - Núcleo Residencial Presidente Geisel, Bauru - SP";
                $cep = isset($_SESSION["user"]["cep"]) ? $_SESSION["user"]["cep"] : "17033-260";
                $coupon = isset($_SESSION["user"]["coupon"]) ? $_SESSION["user"]["coupon"] : 0;
                $items = $_SESSION["cart"];
                $itemsQuantity = 0;
                foreach ($items as $id => $amount) {
                    if ($id == "totalprice") {
                        continue;
                    }
                    $itemsQuantity += $amount;
                }
                $total = $_SESSION['cart']['totalprice'];
                $subtotal = $total - $coupon;

            ?>
                    <div class="container-geral-titulo">
                        <h2>Resumo da compra</h2>
                    </div>
                    <div class="container-geral-conteudo">
                        <?php
                        echo "
                            <p>Itens ($itemsQuantity): <span id='itens'>R$$total</span></p>
                            <p>Desconto: <span id='desconto'>R$$coupon</span></p>
                            <p>Total do pedido: <span id='total'>R$$subtotal</span></p>
                        ";
                        ?>
                    </div>
                    <form action="compra.php" method="post">
                        <div class="container-endereco">
                            <div class="container-endereco-titulo">
                                <h3>Endereço de entrega</h3>
                            </div>
                            <div class="container-endereco-conteudo">
                                <label for="address">Endereço</label>
                                <input type="text" name="address" id="address" placeholder="Seu endereço aqui..." maxlength=255 value="<?php echo $address; ?>" size=60>
                                <label for="cep">CEP</label>
                                <input type="text" id="cep" name="cep" minlength=9 maxlength=9 pattern="[0-9]{5}-[0-9]{3}" placeholder="Seu CEP aqui..."  onkeyup="formatCEP(this, event)" value="<?php echo $cep; ?>">
                            </div>
                        <div class="container-pagamento">
                            <div class="container-pagamento-titulo">
                                <h3>Forma de pagamento</h3>
                            </div>
                            <div class="container-pagamento-conteudo">
                                <div class="container-pagamento-conteudo-tipo">
                                    <input type="radio" name="ficha" id="ficha" value="ficha" checked>
                                    <label for="tipo">Ficha</label>
                                </div>
                                <div class="container-pagamento-cupom">
                                    <label for="cupom">Cupom de desconto</label>
                                    <input type="text" name="cupom" id="cupom" placeholder="Cupom de desconto" value="<?php $coupon == 0 ? '' : $coupon; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="container-finalizar">
                            <input type="submit" value="Finalizar compra">
                        </div>
                    </form>
            
                </div> 
            </div>
    </body>
</html>