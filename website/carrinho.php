<?php
    include("util.php");
    $sessID = startSession();
    $connection = connect();
    isset($_GET['id']) ? $id = $_GET['id'] : "";
    $url = isset($_GET['url']) ? $_GET['url'] : "carrinho.php";
    if(isset($_GET['message'])) {
        echo "<script>alert(" . $_GET['message'] . ");</script>";
        header("refresh: 0; url=$url");
    }
    if(isset($id)) {
        $select = $connection->prepare("SELECT nome, preco, descricao, categoria, imagem, quantidade_estoque FROM tbl_produto WHERE id_produto = $id AND excluido = false");
        $select->execute();
        $row = $select->fetch(PDO::FETCH_ASSOC);
        if ($row == NULL) {
            header("location: $url?message='Produto não encontrado!'");
            die();
        }
    }
    
    $userID = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : NULL;
    $selectCompra = $connection->prepare("SELECT id_compra FROM tbl_compra JOIN tbl_tmp_compra ON tbl_compra.id_compra = tbl_tmp_compra.compra WHERE sessao = :session AND status = 'PENDENTE' AND usuario " . ($userID == NULL ? "IS NULL" : "= $userID"));
    $selectCompra->execute(['session' => $sessID]);
    $resultCompra = $selectCompra->fetch(PDO::FETCH_ASSOC);
    $id_compra = $resultCompra == NULL ? NULL : $resultCompra['id_compra'];

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        if (isset($_GET['id'])) {
            $selectCompraProduto = $connection->prepare("SELECT quantidade FROM tbl_compra_produto WHERE compra = :id_compra AND produto = :id");
            $selectCompraProduto->execute(['id_compra' => $id_compra, 'id' => $id]);
            $resultCompraProduto = $selectCompraProduto->fetch(PDO::FETCH_ASSOC);
            $amount = $_GET['amount'];
            $id = $_GET['id'];
            $stock = $row['quantidade_estoque'];
            $amountCart = $resultCompraProduto != NULL ? $resultCompraProduto['quantidade'] : 0;

            if(isset($_GET['amount'])) {
                if ($action == 'add' && $id_compra == NULL) {
                    $insert = $connection->prepare("INSERT INTO tbl_compra (status, data, usuario) VALUES ('PENDENTE', :date, :user)");
                    $insert->execute(['date' => date('Y-m-d'), 'user' => $userID]);
                    $id_compra = $connection->lastInsertId();
                    $insert = $connection->prepare("INSERT INTO tbl_tmp_compra (sessao, compra) VALUES (:session, :id)");
                    $insert->execute(['session' => $sessID, 'id' => $id_compra]);
                } else if ($id_compra == NULL) {
                    header("location: $url?message='Não há produtos no carrinho!'");
                    die();
                }
                
                if ($action == 'add') {
                    if ($amount > $stock) {
                        header("location: $url?message='Não há estoque suficiente para essa quantidade!'");
                        die();
                    }
                    if ($amountCart != 0) {
                        $amountCart += $amount;
                        $update = $connection->prepare("UPDATE tbl_compra_produto SET quantidade = :amount WHERE compra = :id AND produto = :id_produto");
                        $update->execute(['amount' => $amountCart, 'id' => $id_compra, 'id_produto' => $id]);
                        $updateProduto = $connection->prepare("UPDATE tbl_produto SET quantidade_estoque = :amount WHERE id_produto = :id");
                        $updateProduto->execute(['amount' => $stock - $amount, 'id' => $id]);
                    } else {
                        $insert = $connection->prepare("INSERT INTO tbl_compra_produto (compra, produto, quantidade) VALUES (:id, :id_produto, :amount)");
                        $insert->execute(['id' => $id_compra, 'id_produto' => $id, 'amount' => $amount]);
                        $updateProduto = $connection->prepare("UPDATE tbl_produto SET quantidade_estoque = :amount WHERE id_produto = :id");
                        $updateProduto->execute(['amount' => $stock - $amount, 'id' => $id]);
                    }
                } else if ($action == 'remove') {
                    $amountCart -= $amount;
                    if ($amountCart < 0) {
                        header("location: $url?message='Não é possível remover essa quantidade!'");
                        die();
                    }
                    $countTotalAmountCart = $connection->prepare("SELECT COUNT(*) FROM tbl_compra_produto WHERE compra = :id_compra");
                    $countTotalAmountCart->execute(['id_compra' => $id_compra]);
                    $resultTotalAmountCart = $countTotalAmountCart->fetch(PDO::FETCH_ASSOC);
                    $totalAmountCart = $resultTotalAmountCart['count'];
                    if ($amountCart != 0) {
                        $update = $connection->prepare("UPDATE tbl_compra_produto SET quantidade = :amount WHERE compra = :id AND produto = :id_produto");
                        $update->execute(['amount' => $amountCart, 'id' => $id_compra, 'id_produto' => $id]);
                        $updateProduto = $connection->prepare("UPDATE tbl_produto SET quantidade_estoque = :amount WHERE id_produto = :id");
                        $updateProduto->execute(['amount' => $stock + $amount, 'id' => $id]);
                    } else {
                        $delete = $connection->prepare("DELETE FROM tbl_compra_produto WHERE compra = :id AND produto = :id_produto");
                        $delete->execute(['id' => $id_compra, 'id_produto' => $id]);
                        $updateProduto = $connection->prepare("UPDATE tbl_produto SET quantidade_estoque = :amount WHERE id_produto = :id");
                        $updateProduto->execute(['amount' => $stock + $amount, 'id' => $id]);
                        if ($totalAmountCart == 1) {
                            $deleteTmpCompra = $connection->prepare("DELETE FROM tbl_tmp_compra WHERE sessao = :session AND compra = :id_compra");
                            $deleteTmpCompra->execute(['session' => $sessID, 'id_compra' => $id_compra]);
                            $deleteCompra = $connection->prepare("DELETE FROM tbl_compra WHERE id_compra = :id");
                            $deleteCompra->execute(['id' => $id_compra]);
                        }
                    }
                }
            } else if ($action == 'delete') {
                $delete = $connection->prepare("DELETE FROM tbl_compra_produto WHERE compra = :id AND produto = :id_produto");
                $delete->execute(['id' => $id_compra, 'id_produto' => $id]);
                $updateProduto = $connection->prepare("UPDATE tbl_produto SET quantidade_estoque = :amount WHERE id_produto = :id");
                $updateProduto->execute(['amount' => $stock + $amountCart, 'id' => $id]);
                $deleteTmpCompra = $connection->prepare("DELETE FROM tbl_tmp_compra WHERE sessao = :session AND compra = :id_compra");
                $deleteTmpCompra->execute(['session' => $sessID, 'id_compra' => $id_compra]);
                $deleteCompra = $connection->prepare("DELETE FROM tbl_compra WHERE id_compra = :id");
                $deleteCompra->execute(['id' => $id_compra]);
            }
            $amount = $amountCart;
        } else if ($action == 'clear') {
            if ($id_compra == NULL) {
                header("location: $url?message='Não há produtos no carrinho!'");
                die();
            }
            $delete = $connection->prepare("DELETE FROM tbl_compra_produto WHERE compra = :id");
            $delete->execute(['id' => $id_compra]);
            $deleteTmpCompra = $connection->prepare("DELETE FROM tbl_tmp_compra WHERE sessao = :session AND compra = :id_compra");
            $deleteTmpCompra->execute(['session' => $sessID, 'id_compra' => $id_compra]);
            $deleteCompra = $connection->prepare("DELETE FROM tbl_compra WHERE id_compra = :id");
            $deleteCompra->execute(['id' => $id_compra]);
        }
        $location = "location: $url" . ($url == "produtos.php" ? "?message='Produto adicionado ao carrinho!'" : "");
        header($location);
    } else {
        $selectCompraGuest = $connection->prepare("SELECT id_compra FROM tbl_compra JOIN tbl_tmp_compra ON tbl_compra.id_compra = tbl_tmp_compra.compra WHERE usuario IS NULL AND sessao = :session AND status = 'PENDENTE'");
        $selectCompraGuest->execute(['session' => $sessID]);
        $resultCompraGuest = $selectCompraGuest->fetch(PDO::FETCH_ASSOC);
        $id_compraGuest = $resultCompraGuest == NULL ? NULL : $resultCompraGuest['id_compra'];

        if ($userID != NULL && $id_compraGuest != NULL) {
            if ($id_compra != NULL) {
                $selectCompraProdutoGuest = $connection->prepare("SELECT produto, quantidade FROM tbl_compra_produto WHERE compra = :id_compra");
                $selectCompraProdutoGuest->execute(['id_compra' => $id_compraGuest]);
                $resultCompraProdutoGuest = $selectCompraProdutoGuest->fetchAll(PDO::FETCH_ASSOC);
                $selectCompraProduto = $connection->prepare("SELECT produto, quantidade FROM tbl_compra_produto WHERE compra = :id_compra");
                $selectCompraProduto->execute(['id_compra' => $id_compra]);
                $resultCompraProduto = $selectCompraProduto->fetchAll(PDO::FETCH_ASSOC);
                $insert = $connection->prepare("INSERT INTO tbl_compra_produto (compra, produto, quantidade) VALUES (:id, :id_produto, :amount)");
                $products = array();
                $productsInBoth = array();
                foreach ($resultCompraProdutoGuest as $row) {
                    array_push($products, array($row['produto'], $row['quantidade']));
                    foreach ($resultCompraProduto as $row2) {
                        if ($row['produto'] == $row2['produto']) {
                            array_push($productsInBoth, array($row['produto'], $row2['quantidade']));
                            $amount = $row['quantidade'] + $row2['quantidade'];
                            $updateCompraProduto = $connection->prepare("UPDATE tbl_compra_produto SET quantidade = :amount WHERE compra = :id AND produto = :id_produto");
                            $updateCompraProduto->execute(['amount' => $amount, 'id' => $id_compra, 'id_produto' => $row['produto']]);
                        }
                    }
                }
                foreach ($products as $row) {
                    $exists = false;
                    foreach ($productsInBoth as $row2) {
                        if ($row[0] == $row2[0]) {
                            $exists = true;
                            break;
                        }
                    }
                    if (!$exists) {
                        $insert->execute(['id' => $id_compra, 'id_produto' => $row[0], 'amount' => $row[1]]);
                    }
                }
                $delete = $connection->prepare("DELETE FROM tbl_compra_produto WHERE compra = :id");
                $delete->execute(['id' => $id_compraGuest]);
                $deleteTmpCompra = $connection->prepare("DELETE FROM tbl_tmp_compra WHERE sessao = :session AND compra = :id_compra");
                $deleteTmpCompra->execute(['session' => $sessID, 'id_compra' => $id_compraGuest]);
                $deleteCompra = $connection->prepare("DELETE FROM tbl_compra WHERE id_compra = :id");
                $deleteCompra->execute(['id' => $id_compraGuest]);

            } else {
                $update = $connection->prepare("UPDATE tbl_compra SET usuario = :id WHERE id_compra = :id_compra");
                $update->execute(['id' => $userID, 'id_compra' => $id_compraGuest]);
                $id_compra = $id_compraGuest;
            }
        }
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
        <script src="./js/carrinho.js"></script>
        <title>Carrinho | Mascotero</title>


        <!-- Adicionado para que o símbolo do carrinho esteja azul quando o usuário está na página -->
        <style>
          #simbolo-carrinho {
             filter: brightness(0) invert(19%) sepia(95%) saturate(3970%) hue-rotate(244deg) brightness(101%) contrast(106%);
            }
        </style>
    </head>
    <body>
        <header id="header">
            <?php
                $isAdmin = isset($_SESSION['user']['isAdmin']) ? $_SESSION['user']['isAdmin'] : false;
                header_pag($isAdmin, "carrinho.php");
            ?>
        </header>
        <div id="content">
            <div class="container-geral-nocenter">
                <div id="titulo-carrinho">
                    <span class="texto-destaque">Meu carrinho</span>
                </div>
                <div id="container-lista-produtos">
                    <div id="container-carrinho-produtos">
                        <?php
                        $products = array();
                        if ($id_compra == NULL) {
                            echo "<span class='texto-destaque'>Seu carrinho está vazio!</span>";
                        }
                        $selectCompraProduto = $connection->prepare("SELECT produto, quantidade FROM tbl_compra_produto WHERE compra = :id_compra");
                        $selectCompraProduto->execute(['id_compra' => $id_compra]);
                        $resultCompraProduto = $selectCompraProduto->fetchAll(PDO::FETCH_ASSOC);
                        $totalprice = 0;
                        foreach ($resultCompraProduto as $row) {
                            $id = $row['produto'];
                            $amount = $row['quantidade'];
                            $select = $connection->prepare("SELECT id_produto, nome, preco, descricao, categoria, imagem, quantidade_estoque FROM tbl_produto WHERE id_produto = $id AND excluido = false");
                            $select->execute();
                            $row = $select->fetch(PDO::FETCH_ASSOC);
                            if ($row == NULL) {
                                $delete = $connection->prepare("DELETE FROM tbl_compra_produto WHERE produto = $id");
                                $delete->execute();
                                continue;
                            }
                            $categoryID = $row['categoria'];
                            $selectCategory = $connection->prepare("SELECT nome FROM tbl_categoria WHERE id_categoria = $categoryID");
                            $selectCategory->execute();
                            $row2 = $selectCategory->fetch(PDO::FETCH_ASSOC);
                            $id = $row['id_produto'];
                            $name = $row['nome'];
                            $price = $row['preco'];
                            $description = $row['descricao'];
                            $image = $row['imagem'];
                            $stock = $row['quantidade_estoque'];
                            $category = $row2['nome'];
                            array_push($products, array($id, $row['nome'], $row['preco'], $row['descricao'], $category, $row['imagem'], $row['quantidade_estoque'], $amount));
                            $totalprice += $price * $amount;
                            $price = number_format($price, 2, ',', '.');

                            echo "
                            <div class='produto-carrinho'>
                                <div class='conteudo-carrinho'>
                                    <div class='texto-carrinho'>
                                        <span class='titulo-carrinho'>$name</span>
                                        <span class='tags-produto'>$category</span>
                                        <span class='descricao-produto'>$description</span>
                                        <div class='container-preco-aumentar'>
                                            <span class='texto-destaque'>R$$price</span>
                                            <div class='aumentar-diminuir'>
                                                <a href='carrinho.php?id=$id&action=remove&amount=1&url=carrinho.php'><img src='./imagens/Diminuir_qtd.svg' class='diminuir'></a>
                                                <span class='qtd-numero-carrinho'>$amount</span>
                                                <img src='./imagens/Aumentar_qtd.svg' class='aumentar' onclick='verificateAdd($stock, $id)'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='imagem-carrinho'>
                                        <img src='./imagens/produtos/$image'>
                                    </div>
                                    <!-- <div class='container-remover'>
                                         <a href='carrinho.php?id=$id&action=delete&url=carrinho.php'><img src='./imagens/remover.jpg' class='remover' width='30px' height='30px' alt='Remover produto'></a>
                                    </div> -->
                                </div>
                            </div>
                            ";
                        }
                        ?>
                    </div>
                    <?php if ($id_compra != NULL) { ?>
                        <div id="container-carrinho-lista">
                            <div id="carrinho-lista">
                                <span id="carrinho-cabecalho">Sua compra</span>
                                <div class="separador">
                                    <div class="bola-separador"></div>
                                </div>
                                <div id="lista-compras-carrinho">
                                    <?php
                                        foreach($products as $row) {
                                            $amount = $row[7];
                                            $price = number_format($row[2] * $amount, 2, ',', '.');
                                            $name = $row[1];
                                            echo "
                                            <span class='nome-produto-carrinho'>$name x$amount</span>
                                            <span class='preco-produto-carrinho'>R$$price</span>
                                            ";
                                        }

                                        
                                    ?>
                                </div>
                                <div class="separador">
                                    <div class="bola-separador"></div>
                                </div>
                                <div id="resultado-carrinho">
                                    <span class="nome-produto-carrinho">Total</span>
                                    <span class="preco-produto-carrinho">R$<?php echo number_format($totalprice, 2, ',', '.') ?></span>
                                </div>
                                <div class="centraliza">
                                    <a href="compra.php" id="finalizar-compra"><button class="botao-finalizar">Finalizar compra</button></a>
                                </div>
                                <div class="centraliza">
                                    <a href="carrinho.php?action=clear&url=carrinho.php" id="limpar-carrinho"><button class="botao-finalizar">Limpar carrinho</button></a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <footer id="footer">
            <div id="footer-lado1">
                <div class="container-logo">
                    <img src="imagens/MC_Logo_Footer.svg" width="75px" height="75px"> Mascotero     
                </div>
            </div>
            <div id="footer-lado2">Copyright © 2023 Mascotero Ltda | Todos os direitos reservados</div>
        </footer>

    </body>
</html>