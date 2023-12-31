<?php
    include("util.php");
    startSession(NULL);
    $filter = isset($_GET['categoria']) ? $_GET['categoria'] : '';
    $filter = swapSynonyms($filter);
    $filtro = $filter;
    $filtro = ($filtro == "cti") ? "CTI" : 
          (($filtro == "info") ? "Informática" : 
          (($filtro == "mec") ? "Mecânica" : 
          (($filtro == "eletro") ? "Eletrônica" : $filtro)));

?>

<!DOCTYPE html>
<html lang="pt-BR">
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
        <img src="./imagens/Lupa-Pesquisar.svg">
            <div class="container-geral">
                <div id="barra-pesquisa">
                    <form method="POST" action="produtos.php">
                    <input type="text" name="pesquisa" id="pesquisa" 
                        placeholder="Pesquisar..."  
                        \>
                    </form>
                    <div id="dropdown-corpo">
                        <div id="filtro"> 
                            Filtros  <img src="./imagens/Seta-dropdown-baixo.svg"> 
                        </div>
                            <div id="dropdown">
                                <span class="texto-destaque">Curso:</span>
                                <div class="checkboxes">
                                    <div class="linha-checkbox">
                                        <input type="checkbox" id="cti" name="cti" <?php echo strtolower($filter) == 'cti' ? 'checked' : '' ?>>
                                        <label for="cti">CTI</label>
                                    </div>
                                    <div class="linha-checkbox">
                                        <input type="checkbox" id="info" name="info" <?php echo strtolower($filter) == 'info' ? 'checked' : '' ?>>
                                        <label for="info">Informática</label>
                                    </div>
                                    <div class="linha-checkbox">
                                        <input type="checkbox" id="mec" name="mec" <?php echo strtolower($filter) == 'mec' ? 'checked' : '' ?>>
                                        <label for="mec">Mecânica</label>
                                    </div>
                                    <div class="linha-checkbox">
                                        <input type="checkbox" id="eletro" name="eletro" <?php echo strtolower($filter) == 'eletro' ? 'checked' : '' ?>>
                                        <label for="eletro">Eletrônica</label>
                                    </div>
                                </div>
                                <span class="texto-destaque">Ordenar por:</span>
                                <div class="checkboxes">
                                    <div class="linha-checkbox">
                                        <img src="./imagens/sort_down.svg" alt="Seta para baixo para indicar ordem decrescente" id="seta-baixo-nome" width="20px" height="20px">
                                        <img src="./imagens/sort_up.svg" alt="Seta para cima para indicar ordem crescente" id="seta-cima-nome" width="20px" height="20px">
                                        <label for="ordem_nome">Nome</label>
                                    </div>
                                    <div class="linha-checkbox">
                                        <img src="./imagens/sort_down.svg" alt="Seta para baixo para indicar ordem decrescente" id="seta-baixo-preco" width="20px" height="20px">
                                        <img src="./imagens/sort_up.svg" alt="Seta para cima para indicar ordem crescente" id="seta-cima-preco" width="20px" height="20px">
                                        <label for="ordem_preco">Preço</label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div id="corpo-filtro-display">
                    Filtros: 
                </div> 
                <div class="centraliza"><div class="texto-destaque">Produtos</div></div>
                <div id="grid-produtos">
                <?php
                    if($isAdmin) {
                        echo "<a id='produto-adicionar' data-categoria='admin' data-nome='Adicionar produto' href='cadastro_produto.php'>
                        <svg width='24' height='75' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                        <path d='M12 22.5C6.20101 22.5 1.5 17.799 1.5 12C1.5 6.20101 6.20101 1.5 12 1.5C17.799 1.5 22.5 6.20101 22.5 12C22.5 17.799 17.799 22.5 12 22.5ZM12 24C18.6274 24 24 18.6274 24 12C24 5.37258 18.6274 0 12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24Z' />
                        <path d='M12 6C12.4142 6 12.75 6.33579 12.75 6.75V11.25H17.25C17.6642 11.25 18 11.5858 18 12C18 12.4142 17.6642 12.75 17.25 12.75H12.75V17.25C12.75 17.6642 12.4142 18 12 18C11.5858 18 11.25 17.6642 11.25 17.25V12.75H6.75C6.33579 12.75 6 12.4142 6 12C6 11.5858 6.33579 11.25 6.75 11.25H11.25V6.75C11.25 6.33579 11.5858 6 12 6Z'/>
                    </svg>
                    <span>Adicionar</span>
                        </a>";
                    }
                    $connection = connect();
                    $select = $connection->prepare('SELECT nome, preco, quantidade_estoque, descricao, categoria, imagem, id_produto from tbl_produto WHERE excluido = false ORDER BY lower(nome)');
                    $select->execute();
                    $result = $select->fetchAll(PDO::FETCH_ASSOC);

                    foreach($result as $row) {
                        $category = $row['categoria'];
                        $select2 = $connection->prepare('SELECT nome FROM tbl_categoria WHERE id_categoria = :category');
                        $select2->execute(['category' => $category]);
                        $category = $select2->fetch();
                        $category = $category['nome'];
                        $id = $row['id_produto'];
                        $name = $row['nome'];
                        $amount = $row['quantidade_estoque'];
                        $name2 = abreviarTexto($name, 28);
                        $image = $row['imagem'];
                        $price = number_format($row['preco'], 2, ',', '.');
                        isset($row['descricao']) ? $description = $row['descricao'] : $description = '';
                        $description = abreviarTexto($description,85);
                    
                        echo "<div class='produto' data-categoria='$category' data-nome='$name' data-preco = '$price'>
                            <a href='produto.php?id=$id'>
                                <div class='produto-imagem'><img src='imagens/produtos/$image'></div>
                            </a>
                                <div class='produto-corpo'>";
                    
                        echo $isAdmin ? "<a href='alteracao_produto.php?id=$id'>
                        <img src='imagens/editar.png' width='20px' height='20px' class='imagem-editar-produto'></a>" : "";
                    
                        echo "<span class='nome-produto'>$name2</span>
                            <span class='tags-produto'>$category</span>";
                
                        echo isset($description) ? "<span class='descricao-produto'>$description</span>" : "";

                      echo $amount > 0 ? "  
                        <a class='adicionar-carrinho' href='carrinho.php?id=$id&url=produtos.php&action=add&amount=1'>
                            <img src='./imagens/Aumentar_qtd.svg'>
                            <span>Adicionar ao carrinho</span>
                        </a>" : "<span class='adicionar-carrinho'>Produto esgotado</span>";
                        echo "
                        <span class='preco-produto texto-destaque'>R$$price</span>
                            </div>
                            </a>
                        </div>";
                    }
                    if(isset($_SESSION['user']['isAdmin']) && $_SESSION['user']['isAdmin']) {
                        $select = $connection->prepare('SELECT nome, preco, descricao, categoria, imagem, id_produto FROM tbl_produto WHERE excluido = true ORDER BY lower(nome)');
                        $select->execute();
                        $result = $select->fetchAll(PDO::FETCH_ASSOC);

                        foreach($result as $row) {
                            $category = $row['categoria'];
                            $select2 = $connection->prepare('SELECT nome FROM tbl_categoria WHERE id_categoria = :category');
                            $select2->execute(['category' => $category]);
                            $category = $select2->fetch();
                            $category = $category['nome'];
                            $id = $row['id_produto'];
                            $name = $row['nome'];
                            $image = $row['imagem'];
                            $price = number_format($row['preco'], 2, ',', '.');
                            $name2 = abreviarTexto($name, 20);
                            isset($row['descricao']) ? $description = abreviarTexto($row['descricao'],50) : $description = '';

                            echo "<div class='produto deletado' data-categoria='$category' data-nome='$name' data-preco = '$price'>
                            <a href='produto.php?id=$id'>
                                <div class='produto-imagem'><img src='imagens/produtos/$image'></div>
                                <div class='produto-corpo'>";
                        
                            echo "<a href='alteracao_produto.php?id=$id'>
                            <img src='imagens/editar.png' width='20px' height='20px' class='imagem-editar-produto'></a>";
                            
                            echo "<span class='nome-produto'>$name2 - DELETADO</span>
                                <span class='tags-produto'>$category</span>";
                    
                            echo isset($description) ? "<span class='descricao-produto'>$description</span>" : "";
                        
                            echo "<span class='preco-produto texto-destaque'>R$$price</span>
                                </div>
                            </div>";
                        }
                    }
                ?>
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
<script src="js/produtos.js"></script>
<script>
filterProducts();
if ("<?php echo $filtro?>" != "")
criarElemento("<?php echo $filtro ?>")
</script>
<?php
    if(isset($_GET['message'])) {
        echo "<script>alert(" . $_GET['message'] . ");</script>";
        header("refresh: 0; url=./produtos.php");
    }
?>