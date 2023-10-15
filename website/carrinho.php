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
                    $select = $connection->prepare("SELECT * FROM tbl_compra_produto WHERE compra = :id AND produto = :id_produto");
                    $select->execute(['id' => $result['id_compra'], 'id_produto' => $id]);
                    $result2 = $select->fetch(PDO::FETCH_ASSOC);
                    if ($result2 != NULL) {
                        var_dump($amount);
                        if ($amount == 0) {
                            $delete = $connection->prepare("DELETE FROM tbl_compra_produto WHERE compra = :id AND produto = :id_produto");
                            $delete->execute(['id' => $result['id_compra'], 'id_produto' => $id]);
                            $select2 = $connection->prepare("SELECT * FROM tbl_compra_produto WHERE compra = :id");
                            $select2->execute(['id' => $result['id_compra']]);
                            $result2 = $select2->fetch(PDO::FETCH_ASSOC);
                            if ($result2 == NULL) {
                                $delete = $connection->prepare("DELETE FROM tbl_compra WHERE id_compra = :id");
                                $delete->execute(['id' => $result['id_compra']]);
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
                }
            }
        }
        $location = "location: $url" . ($url == "produtos.php" ? "?message='Produto adicionado ao carrinho!'" : "");
        header($location);
    } else if(isset($_SESSION['user']['id']) && (sizeof($_SESSION['cart'])-1 == 0)) {
        $select = $connection->prepare("SELECT id_compra FROM tbl_compra WHERE usuario = :id AND status = 'PENDENTE'");
        $select->execute(['id' => $_SESSION['user']['id']]);
        $result = $select->fetch(PDO::FETCH_ASSOC);
        if ($result != NULL) {
            $select = $connection->prepare("SELECT produto, quantidade FROM tbl_compra_produto WHERE compra = :id");
            $select->execute(['id' => $result['id_compra']]);
            $result = $select->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $_SESSION['cart'][$row['produto']] = $row['quantidade'];
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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,600&display=swap" rel="stylesheet">
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
                        foreach($_SESSION['cart'] as $id => $amount) {
                            if ($id == 'totalprice') {
                                continue;
                            }
                            $select = $connection->prepare("SELECT id_produto, nome, preco, descricao, categoria, imagem, quantidade_estoque FROM tbl_produto WHERE id_produto = $id AND excluido = false");
                            $select->execute();
                            $row = $select->fetch(PDO::FETCH_ASSOC);
                            $category = $row['categoria'];
                            $select2 = $connection->prepare("SELECT nome FROM tbl_categoria WHERE id_categoria = $category");
                            $select2->execute();
                            $row2 = $select2->fetch(PDO::FETCH_ASSOC);

                            $id = $row['id_produto'];
                            $name = $row['nome'];
                            $price = $row['preco'];
                            $description = $row['descricao'];
                            $category = $row['categoria'];
                            $image = $row['imagem'];
                            $stock = $row['quantidade_estoque'];
                            $category = $row2['nome'];

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
                                    <div class='container-remover'>
                                        <a href='carrinho.php?id=$id&action=delete&url=carrinho.php'><img src='./imagens/remover.jpg' class='remover' width='30px' height='30px' alt='Remover produto'></a>
                                    </div>
                                </div>
                            </div>
                            ";
                        }
                        ?>
                    </div>
                    <div id="container-carrinho-lista">
                        <div id="carrinho-lista">
                            <span id="carrinho-cabecalho">Sua compra</span>
                            <div class="separador">
                                <div class="bola-separador"></div>
                            </div>
                            <div id="lista-compras-carrinho">
                                <!-- <span class="nome-produto-carrinho">Chaveiro Mascote Informática #1</span>
                                <span class="preco-produto-carrinho">R$2,00</span> -->
                                <?php
                                    $totalprice = 0;
                                    foreach($_SESSION['cart'] as $id => $amount) {
                                        if ($id == 'totalprice') {
                                            continue;
                                        }
                                        $select = $connection->prepare("SELECT id_produto, nome, preco, descricao, categoria, imagem, quantidade_estoque FROM tbl_produto WHERE id_produto = $id AND excluido = false");
                                        $select->execute();
                                        $row = $select->fetch(PDO::FETCH_ASSOC);
                                        $price = $row['preco'];
                                        $totalprice += $price * $amount;
                                        $name = $row['nome'];
                                        $price = number_format(($row['preco'] * $amount), 2, ',', '.');
                                        echo "
                                        <span class='nome-produto-carrinho'>$name x$amount</span>
                                        <span class='preco-produto-carrinho'>R$$price</span>
                                        ";
                                    }
                                    $_SESSION['cart']['totalprice'] = $totalprice;
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