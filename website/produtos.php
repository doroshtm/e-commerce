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
                                <span class="texto-destaque">Curso:</span>
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
                    $select = $connection->prepare('SELECT nome, preco, descricao, categoria, imagem, id_produto from tbl_produto WHERE excluido = false ORDER BY lower(nome)');
                    $select->execute();
                    $result = $select->fetchAll(PDO::FETCH_ASSOC);

                    foreach($result as $row) {
                        $category = $row['categoria'];
                        $select2 = $connection->prepare('SELECT nome from tbl_categoria where id_categoria = :categoria');
                        $select2->execute(['categoria' => $category]);
                        $category = $select2->fetch();
                    
                        echo "<div class='produto' data-categoria='". $category['nome'] ."' data-nome='" . $row['nome'] . "' data-preco = '" . $row['preco'] . "'>
                            <div class='produto-imagem'><img src='imagens/produtos/" . $row['imagem'] . "'></div>
                            <div class='produto-corpo'>";
                    
                        echo $isAdmin ? "<a href='alteracao_produto.php?id=" . $row['id_produto'] . "'>
                        <img src='imagens/editar.png' width='20px' height='20px' class='imagem-editar-produto'></a>" : "";
                    
                        echo "<span class='nome-produto'>" . $row['nome'] . "</span>
                            <span class='tags-produto'>" . $category['nome'] . "</span>";
                
                        echo isset($row['descricao']) ? "<span class='descricao-produto'>" . $row['descricao'] . "</span>" : "";
                    
                        echo "<span class='preco-produto texto-destaque'>R$ " . number_format($row['preco'], 2, ',', '.') . "</span>
                            </div>
                        </div>";
                    }
                    if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
                        $select = $connection->prepare('SELECT nome, preco, descricao, categoria, imagem, id_produto from tbl_produto WHERE excluido = true ORDER BY lower(nome)');
                        $select->execute();
                        $result = $select->fetchAll(PDO::FETCH_ASSOC);

                        foreach($result as $row) {
                            $category = $row['categoria'];
                            $select2 = $connection->prepare('SELECT nome from tbl_categoria where id_categoria = :category');
                            $select2->execute(['category' => $category]);
                            $category = $select2->fetch();
                        
                            echo "<div class='produto deletado' data-categoria='". $category['nome'] ."' data-nome='" . $row['nome'] . "' data-preco = '" . $row['preco'] . "'>
                                <div class='produto-imagem'><img src='imagens/produtos/" . $row['imagem'] . "'></div>
                                <div class='produto-corpo'>";
                        
                            echo "<a href='alteracao_produto.php?id=" . $row['id_produto'] . "'>
                            <img src='imagens/editar.png' width='20px' height='20px' class='imagem-editar-produto'></a>";
                        
                            echo "<span class='nome-produto'>" . $row['nome'] . " - DELETADO</span>
                                <span class='tags-produto'>" . $category['nome'] . "</span>";
                    
                            echo isset($row['descricao']) ? "<span class='descricao-produto'>" . $row['descricao'] . "</span>" : "";
                        
                            echo "<span class='preco-produto texto-destaque'>R$ " . number_format($row['preco'], 2, ',', '.') . "</span>
                                </div>
                            </div>";
                        }
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