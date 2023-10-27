<?php
    include("util.php");
    startSession(NULL);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contatos | Mascotero</title>
    <link rel="stylesheet" href="styles_header_footer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
    <link rel="stylesheet" href="styles_paginas.css">
    <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">

</head>
<body>
    <header id="header">
        <?php
            $isAdmin = isset($_SESSION['user']['isAdmin']) ? $_SESSION['user']['isAdmin'] : false;
            header_pag($isAdmin, "contato.php");
        ?>
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
                        <span class="texto-destaque"> Email: mascotero.ltd@gmail.com</span>
                    </div>
                    <div class="contato">
                        <img src="./imagens/Instagram_logo.svg">
                        <span class="texto-destaque"> Instagram: @mascotero_ltda </span>
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