<?php
    include("util.php");
    $sessID = startSession();
    startCartSession();
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
    $selectCompra = $connection->prepare("SELECT id_compra FROM tbl_compra JOIN tbl_tmp_compra ON tbl_compra.id_compra = tbl_tmp_compra.compra WHERE tbl_tmp_compra.sessao = :sessao AND status = 'PENDENTE'");
    $selectCompra->execute(['sessao' => $sessID]);
    $resultCompra = $selectCompra->fetch(PDO::FETCH_ASSOC);
    if ($resultCompra != NULL) {
        $select = $connection->prepare("SELECT * FROM tbl_compra_produto JOIN tbl_produto ON tbl_compra_produto.produto = $id WHERE compra = :id AND tbl_produto.excluido = false");
        $select->execute(['id' => $resultCompra['id_compra']]);
        $result = $select->fetch(PDO::FETCH_ASSOC);
        if ($result != NULL) {
            $amount = $result['quantidade'];
        } else {
            $amount = 0;
        }
    } else {
        $amount = 0;
    }
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        if (isset($_GET['id'])) {
            if(isset($_GET['amount'])) {
                isset($_SESSION['cart'][$_GET['id']]) ? $amountCart = $_SESSION['cart'][$_GET['id']] : "";
                $amount = $_GET['amount'];
                $id = $_GET['id'];
                $stock = $row['quantidade_estoque'];
                if ($action == 'add') {
                    if (isset($amountCart)) {
                        if ($amountCart + $amount > $stock) {
                            header("location: $url?message='Não há estoque suficiente para essa quantidade!'");
                            die();
                        }
                        $_SESSION['cart'][$id] += $amount;
                    } else {
                        $_SESSION['cart'][$id] = $amount;
                    }
                } else if ($action == 'remove') {
                    if (isset($amountCart)) {
                        $_SESSION['cart'][$id] -= $amount;
                        if ($_SESSION['cart'][$id] <= 0) {
                            unset($_SESSION['cart'][$id]);
                        }
                    }
                }
            } else if ($action == 'delete') {
                unset($_SESSION['cart'][$id]);
            }
            $amount = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id] : 0;   
            if (isset($_SESSION['user']['id'])) {
                $select = $connection->prepare("SELECT id_compra FROM tbl_compra WHERE usuario = :id AND status = 'PENDENTE'");
                $select->execute(['id' => $_SESSION['user']['id']]);
                $result = $select->fetch(PDO::FETCH_ASSOC);
                if ($result != NULL) {
                    $select = $connection->prepare("SELECT * FROM tbl_compra_produto JOIN tbl_produto ON tbl_compra_produto.produto = $id WHERE compra = :id AND tbl_produto.excluido = false");
                    $select->execute(['id' => $result['id_compra']]);
                    $result2 = $select->fetch(PDO::FETCH_ASSOC);
                    if ($result2 != NULL) {
                        if ($amount == 0) {
                            $delete = $connection->prepare("DELETE FROM tbl_compra_produto WHERE compra = :id AND produto = :id_produto");
                            $delete->execute(['id' => $result['id_compra'], 'id_produto' => $id]);
                            $select2 = $connection->prepare("SELECT * FROM tbl_compra_produto WHERE compra = :id");
                            $select2->execute(['id' => $result['id_compra']]);
                            $result2 = $select2->fetch(PDO::FETCH_ASSOC);
                            if ($result2 == NULL) {
                                $delete = $connection->prepare("DELETE FROM tbl_compra WHERE id_compra = :id");
                                $delete->execute(['id' => $result['id_compra']]);
                                unset($_SESSION['cart']['totalprice']);
                            }
                        } else {
                            $update = $connection->prepare("UPDATE tbl_compra_produto SET quantidade = :amount WHERE compra = :id AND produto = :id_produto");
                            $update->execute(['amount' => $amount, 'id' => $result['id_compra'], 'id_produto' => $id]);
                        }
                    } else if ($amount != 0) {
                        $insert = $connection->prepare("INSERT INTO tbl_compra_produto (compra, produto, quantidade) VALUES (:id, :id_produto, :amount)");
                        $insert->execute(['id' => $result['id_compra'], 'id_produto' => $id, 'amount' => $amount]);
                    }
                } else if ($amount != 0) {
                    $insert = $connection->prepare("INSERT INTO tbl_compra (usuario, status, data) VALUES (:id, 'PENDENTE', :date)");
                    $insert->execute(['id' => $_SESSION['user']['id'], 'date' => date('Y-m-d')]);
                    $id_compra = $connection->lastInsertId();
                    $insert = $connection->prepare("INSERT INTO tbl_compra_produto (compra, produto, quantidade) VALUES (:id, :id_produto, :amount)");
                    $insert->execute(['id' => $id_compra, 'id_produto' => $id, 'amount' => $amount]);
                }
            } else {
                $_SESSION['cart']['visitor'] = true;
            }
        } else if ($action == 'clear') {
            unset($_SESSION['cart']);
            if (isset($_SESSION['user']['id'])) {
                $select = $connection->prepare("SELECT id_compra FROM tbl_compra WHERE usuario = :id AND status = 'PENDENTE'");
                $select->execute(['id' => $_SESSION['user']['id']]);
                $result = $select->fetch(PDO::FETCH_ASSOC);
                if ($result != NULL) {
                    $delete = $connection->prepare("DELETE FROM tbl_compra_produto WHERE compra = :id");
                    $delete->execute(['id' => $result['id_compra']]);
                    $delete = $connection->prepare("DELETE FROM tbl_compra WHERE id_compra = :id");
                    $delete->execute(['id' => $result['id_compra']]);
                    unset($_SESSION['cart']['totalprice']);
                }
            }
        }
        $location = "location: $url" . ($url == "produtos.php" ? "?message='Produto adicionado ao carrinho!'" : "");
        header($location);
    } else if(isset($_SESSION['user']['id'])) {
        $select = $connection->prepare("SELECT id_compra FROM tbl_compra WHERE usuario = :id AND status = 'PENDENTE'");
        $select->execute(['id' => $_SESSION['user']['id']]);
        $result = $select->fetch(PDO::FETCH_ASSOC);
        
        if ($result != NULL) {
            if (isset($_SESSION['cart']['visitor'])) {
                $update = $connection->prepare("UPDATE tbl_compra_produto SET quantidade = :amount WHERE compra = :id AND produto = :id_produto");
                foreach ($_SESSION['cart'] as $id => $amount) {
                    if ($id == 'totalprice' || $id == 'visitor') {
                        continue;
                    }
                    $update->execute(['amount' => $amount, 'id' => $result['id_compra'], 'id_produto' => $id]);
                }
                unset($_SESSION['cart']['visitor']);
            }
            if (empty($_SESSION['cart'])) {
                $select = $connection->prepare("SELECT produto, quantidade FROM tbl_compra_produto WHERE compra = :id");
                $select->execute(['id' => $result['id_compra']]);
                $result = $select->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $_SESSION['cart'][$row['produto']] = $row['quantidade'];
                }
            }
        } else if (isset($_SESSION['cart']['visitor'])) {
            $insert = $connection->prepare("INSERT INTO tbl_compra (usuario, status, data) VALUES (:id, 'PENDENTE', :date)");
            $insert->execute(['id' => $_SESSION['user']['id'], 'date' => date('Y-m-d')]);
            $id_compra = $connection->lastInsertId();
            $insert = $connection->prepare("INSERT INTO tbl_compra_produto (compra, produto, quantidade) VALUES (:id, :id_produto, :amount)");
            foreach ($_SESSION['cart'] as $id => $amount) {
                if ($id == 'totalprice' || $id == 'visitor') {
                    continue;
                }
                $insert->execute(['id' => $id_compra, 'id_produto' => $id, 'amount' => $amount]);
            }
            unset($_SESSION['cart']['visitor']);
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
                        if (empty($_SESSION['cart'])) {
                            echo "<span class='texto-destaque'>Seu carrinho está vazio!</span>";

                        }
                        foreach ($_SESSION['cart'] as $id => $amount) {
                            if ($id == 'totalprice' || $id == 'visitor') {
                                continue;
                            }
                            $select = $connection->prepare("SELECT id_produto, nome, preco, descricao, categoria, imagem, quantidade_estoque FROM tbl_produto WHERE id_produto = $id AND excluido = false");
                            $select->execute();
                            $row = $select->fetch(PDO::FETCH_ASSOC);
                            if ($row == NULL) {
                                $delete = $connection->prepare("DELETE FROM tbl_compra_produto WHERE produto = :id");
                                $delete->execute(['id' => $id]);
                                unset($_SESSION['cart'][$id]);
                                continue;
                            }
                            $category = $row['categoria'];
                            $select2 = $connection->prepare("SELECT nome FROM tbl_categoria WHERE id_categoria = $category");
                            $select2->execute();
                            $row2 = $select2->fetch(PDO::FETCH_ASSOC);
                            $id = $row['id_produto'];
                            $name = $row['nome'];
                            $price = $row['preco'];
                            $description = $row['descricao'];
                            $image = $row['imagem'];
                            $stock = $row['quantidade_estoque'];
                            $category = $row2['nome'];
                            array_push($products, array($id, $row['nome'], $row['preco'], $row['descricao'], $row2['nome'], $row['imagem'], $row['quantidade_estoque'], $amount));
                            $price = number_format($price, 2, ',', '.');
                            $totalprice = 0;
                            foreach ($products as $product) {
                                $price = $product[2];
                                $amount = $product[7];
                                $totalprice += $price * $amount;
                            }

                            if ($totalprice == 0) {
                                unset($_SESSION['cart']['totalprice']);
                            } else {
                                $_SESSION['cart']['totalprice'] = $totalprice;
                            }

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
                                                <img src='./imagens/Aumentar_qtd.svg' class='aumentar' onclick='verificateAdd($amount, $stock, $id)'>
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
                    <?php if (!empty($_SESSION['cart'])) { ?>
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