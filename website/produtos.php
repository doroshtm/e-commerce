<?php
    include("util.php");
    $sessID = startSession();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotero</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles_header_footer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
    <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
    <script src="js/produtos.js?v=0.98"></script>
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
            $name = isset($_SESSION['name']) ? explode(' ', $_SESSION['name'], 2)[0] : 'visitante';
            $isAdmin = isset($_SESSION['isAdmin']) ? $_SESSION['isAdmin'] : false;
        ?>
        <div id="container-usuario">
            <span>Olá, </span>
            <span id="nome-usuario"> <?php echo "$name - você é " . ($isAdmin ? 'admin' : 'cliente') ?> </span>
            <a href = './login.php'> <img src="imagens/user_icon.svg" alt="Foto do cliente"> </a>
            <img src="imagens/carrinho.svg" alt="Carrinho de compras">
        </div>
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
                        </div>
                </div>
            </div>
            <div id="corpo-filtro-display">
                Filtros: 
            </div>
            <div class="centraliza"><div class="texto-destaque">Produtos</div></div>
            <div id="grid-produtos">
            <?php
                $connection = connect();
                $select = $connection->prepare('select nome, preco, descricao, categoria, imagem, id_produto from tbl_produto WHERE excluido = false');
                $select->execute();
                
                while ($linha = $select->fetch()) {
                    $categoria = $linha['categoria'];
                    $select2 = $connection->prepare('select nome from tbl_categoria where id_categoria = :categoria');
                    $select2->execute(['categoria' => $categoria]);
                    $categoria = $select2->fetch();
                    echo "<div class='produto' data-categoria='". $categoria['nome'] ."' data-nome='" . $linha['nome'] . "'>
                    <div class='produto-imagem'><img src='imagens/produtos/" . $linha['imagem'] . "'></div>
                    <div class='produto-corpo'>
                        <span class='nome-produto'>" . $linha['nome'] . "</span>
                        <span class='tags-produto'>" . $categoria['nome'] . "</span>";
                        echo isset($linha['descricao']) ? "<span class='descricao-produto'>" . $linha['descricao'] . "</span>" : "";
                        echo "<span class='preco-produto texto-destaque'>R$ " . number_format($linha['preco'], 2, ',', '.') . "</span>
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