<?php
    include("util.php");
    startSession();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotero</title>
    <link rel="stylesheet" href="styles_header_footer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
    <link rel="stylesheet" href="styles_paginas.css">
    <link rel="icon" type="image/x-cion" href="/website/imagens/MC_Logo_Footer.svg">

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
                <li><a href="produtos.php">Produtos</a></li>
                <li><a href="contato.php" id="nav-atual">Contato</a></li>
            </ul>
        </nav>
        <?php
            $name = isset($_SESSION['name']) ? explode(' ', $_SESSION['name'], 2)[0] : 'visitante';
            $isAdmin = isset($_SESSION['isAdmin']) ? $_SESSION['isAdmin'] : false;
        ?>
        <div id="container-usuario">
            <span>Olá, </span>
            <span id="nome-usuario"> <?php echo "$name - você é " . ($isAdmin ? 'admin' : 'cliente') ?> </span>
            <img src="imagens/user_icon.svg" alt="Foto do cliente">
            <img src="imagens/carrinho.svg" alt="Carrinho de compras">
        </div>
    </header>
    <div id="content">
        <div class="container-geral">
            <div id="contato-corpo">
                <div id="contato-card">
                    <span class="texto-destaque">Contato</span>
                    <div id="contato-card-content">
                    <div class="contato">
                        <img src="./imagens/Whatsapp_logo.svg">
                        <span class="texto-destaque"> Whatsapp: (14) 99891-2007 </span>
                    </div>
                    <div class="contato">
                        <img src="./imagens/Email_logo.svg">
                        <span class="texto-destaque" style="font-size:20px;"> Email: fernando.theodoro@unesp.br</span>
                    </div>
                    <div class="contato">
                        <img src="./imagens/Instagram_logo.svg">
                        <span class="texto-destaque"> Instagram: @emy_r_0103 </span>
                    </div>
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
        <div id="footer-lado2">Copyright © 2023 Mascotero Ltda | Todos os direitos reservados</div>
    </footer>
</body>
</html>