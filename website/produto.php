<?php
    include("util.php");
    startSession(NULL);
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
                    <img src="./imagens/Eletro1.jpg">
                </div>
                <div id="container-tela-produto-descricao">
                    <div id="interior-tela-produto-descricao">
                        <span id="tela-produto-titulo">
                            Nome mascote teste isaac portal roldán
                        </span>
                        <span id="tela-produto-tag">
                            Eletrônica
                        </span>
                        <span id="tela-produto-descricao">
                            Esse é um mascote de teste para o isaac portal rolddán soiadaoip shfaipsjfi´pdshfipw
                        </span>
                        <span id="tela-produto-preco">
                            R$2,00
                        </span>   
                        <span id="tela-produto-adicionar-carrinho">
                            Adicionar ao carrinho
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>