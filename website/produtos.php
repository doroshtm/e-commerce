<?php
    include("util.php");
    startSession();
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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
        <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
        <script src="js/produtos.js?v=1.09"></script>
    </head>
    <body>
        <header id="header">
            <div class="container-logo"> 
                <div id="imagem-logo"><img src="imagens/MC_Logo_Header.svg"> </div>
                <div id="texto-logo">Mascotero</div>
            </div>
            <nav id="nav-header">
                <ul>
                    <li><a href="./">Home</a></li>
                    <li><a href="sobre.php">Sobre</a></li>
                    <li><a href="produtos.php" id="nav-atual">Produtos</a></li>
                    <li><a href="contato.php">Contato</a></li>
                </ul>
            </nav>
            <?php
                $isAdmin = isset($_SESSION['isAdmin']) ? $_SESSION['isAdmin'] : false;
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
                                <span class="texto-destaque">Cursos:</span>
                                <div class="checkboxes">
                                    <div class="linha-checkbox">
                                        <input type="checkbox" id="cti" name="cti">
                                        <label for="cti">CTI</label>
                                    </div>
                                    <div class="linha-checkbox">
                                        <input type="checkbox" id="info" name="info"> 
                                        <label for="info">Informática</label>
                                    </div>
                                    <div class="linha-checkbox">
                                        <input type="checkbox" id="mec" name="mec"> 
                                        <label for="mec">Mecânica</label>
                                    </div>
                                    <div class="linha-checkbox">
                                        <input type="checkbox" id="eletro" name="eletro"> 
                                        <label for="eletro">Eletrônica</label>
                                    </div>
                                </div>
                                <span class="texto-destaque">Ordenar por:</span>
                                <div class="checkboxes">
                                    <div class="linha-checkbox">
                                        <img src="./imagens/seta_baixo.jpg" alt="Seta para baixo para indicar ordem decrescente" id="seta-baixo-nome" width="20px" height="20px">
                                        <img src="./imagens/seta_cima.jpeg" alt="Seta para cima para indicar ordem crescente" id="seta-cima-nome" width="20px" height="20px">
                                        <label for="ordem_nome">Nome</label>
                                    </div>
                                    <div class="linha-checkbox">
                                        <img src="./imagens/seta_baixo.jpg" alt="Seta para baixo para indicar ordem decrescente" id="seta-baixo-preco" width="20px" height="20px">
                                        <img src="./imagens/seta_cima.jpeg" alt="Seta para cima para indicar ordem crescente" id="seta-cima-preco" width="20px" height="20px">
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
                        echo "<a class='produto' data-categoria='admin' data-nome='Adicionar produto' href='cadastro_produto.php'>
                        <div class='produto-imagem'><img src='imagens/produtos/adicionar.png'></div>
                        <div class='produto-corpo'>
                            <span class='nome-produto'>Adicionar produto</span>
                            <span class='tags-produto'>admin</span>
                            <span class='preco-produto texto-destaque'>++++++++++++++</span>
                        </div> </a>";
                    }
                    $connection = connect();
                    $select = $connection->prepare('select nome, preco, descricao, categoria, imagem, id_produto from tbl_produto WHERE excluido = false ORDER BY lower(nome)');
                    $select->execute();
                    $result = $select->fetchAll(PDO::FETCH_ASSOC);

                    foreach($result as $row) {
                        $category = $row['categoria'];
                        $select2 = $connection->prepare('select nome from tbl_categoria where id_categoria = :categoria');
                        $select2->execute(['categoria' => $category]);
                        $category = $select2->fetch();
                        echo "<div class='produto' data-categoria='". $category['nome'] ."' data-nome='" . $row['nome'] . "' data-preco = '" . $row['preco'] . "'>
                        <div class='produto-imagem'><img src='imagens/produtos/" . $row['imagem'] . "'></div>
                        <div class='produto-corpo'>
                            <span class='nome-produto'>" . $row['nome'] . "</span>
                            <span class='tags-produto'>" . $category['nome'] . "</span>";
                            echo isset($row['descricao']) ? "<span class='descricao-produto'>" . $row['descricao'] . "</span>" : "";
                            echo $isAdmin ? "<a href='alteracao_produto.php?id=" . $row['id_produto'] . "'><img src='imagens/editar.png' width='20px' height='20px'></a>" : "";
                            echo "<span class='preco-produto texto-destaque'>R$ " . number_format($row['preco'], 2, ',', '.') . "</span>
                        </div> </div>";
                    }
                ?>
                </div>
            </div>
        </div>
        <footer id="footer">
            <div id="footer-lado1" style="align-items:center;">
                <div class="container-logo">
                    <img src="imagens/MC_Logo_Footer.svg" width="75px" height="75px" alt= "Logo do footer"> Mascotero        
                </div>
            </div>
            <div id="footer-lado2">Copyright © 2023 Mascotero Ltda | Todos os direitos reservados</div>
        </footer>
    </body>
</html>