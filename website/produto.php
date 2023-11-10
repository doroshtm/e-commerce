<?php
    $id = $_GET['id'];
    include("util.php");
    startSession(NULL);
    $connection = connect();
    $query = 'SELECT nome, preco, quantidade_estoque, descricao, categoria, imagem, id_produto FROM tbl_produto WHERE excluido = false AND 
    id_produto= :id ORDER BY lower(nome)';
    $select = $connection->prepare($query);
    $select->execute(['id' => $id]);
    $row = $select->fetch(PDO::FETCH_ASSOC);
    $category = $row['categoria'];
    $select2 = $connection->prepare('SELECT nome FROM tbl_categoria WHERE id_categoria = :category');
    $select2->execute(['category' => $category]);
    $category = $select2->fetch();
    $category = $category['nome'];
    $name = $row['nome'];
    $amount = $row['quantidade_estoque'];
    $image = $row['imagem'];
    $price = number_format($row['preco'], 2, ',', '.');
    isset($row['descricao']) ? $description = $row['descricao'] : $description = '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | Mascotero</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles_header_footer.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400;1,9..40,500&display=swap" rel="stylesheet">         
    <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
</head>
<body>
    <header id="header">
        <?php
            $isAdmin = isset($_SESSION['user']['isAdmin']) ? $_SESSION['user']['isAdmin'] : false;
            header_pag($isAdmin, "produtos.php");
        ?>
    </header>
    <div id="content">
        <div class="container-geral">
            <div id="container-tela-produto">
                <div id="container-tela-produto-imagem">
                    <img src="imagens/produtos/<?php echo $image; ?>" alt="foto do produto">
                </div>
                <div id="container-tela-produto-descricao">
                    <div id="interior-tela-produto-descricao">
                        <span id="tela-produto-titulo">
                            <?php echo $name; ?>
                        </span>
                        <span id="tela-produto-tag">
                            <?php echo $category; ?>
                        </span>
                        <span id="tela-produto-descricao">
                            <?php echo $description; ?>
                        </span>
                        <span id="tela-produto-preco">
                            R$ <?php echo $price; ?>
                        </span>   
                        <a id="tela-produto-adicionar-carrinho" href='carrinho.php?id=<?php echo $id ?>&url=produtos.php&action=add&amount=1'>
                            Adicionar ao carrinho
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer id="footer">
            <div id="footer-lado1">
                <div class="container-logo">
                    <img src="imagens/MC_Logo_Footer.svg" width="75px" height="75px" alt= "Logo do footer"> Mascotero
                </div>
            </div>
            <a id="footer-lado2" href="sobre.php">
                    <span class="texto-destaque">Desenvolvedores</span>
                    <span>11 - Ellen Carvalho </span>
                    <span>12 - Emily Rocha</span>
                    <span>13 - Fernando Theodoro </span>
                    <span>14 - Gabriel Carraro </span>
                    <span>15 - Gabriel Menegazzo </span>
            </a>
        </footer>
</body>
</html>