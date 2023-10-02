<!DOCTYPE html>
<html lang="en">
<?php
        include("util.php");
        startSession();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotero</title>
    <link rel="stylesheet" href="./styles_header_footer.css">
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
                <li><a href="#" id="nav-atual">Home</a></li>
                <li><a href="sobre.html">Sobre</a></li>
                <li><a href="produtos.html">Produtos</a></li>
                <li><a href="contato.html">Contato</a></li>
                <li><a href="estatisticas.html">Estatísticas</a></li>
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
    <div class="container-geral">
        <div id="display-produtos">
        </div>
        <div id="container-bolas">
            <div class="bolamaior">
                <div class="bolamenor"></div>
            </div>
            <div class="bolamaior"></div>
        </div>
        <span class="texto-destaque">O que fazemos</span>
        <div id="container-mascote-produtos">
            <div class="conteudo-mp">
                <div class="texto-mp">
                    <span class="texto-destaque">Nosso mascote <br></span>
                    <span>Nosso mascote, roberson, é um mascote feito para represtar o CTI com sucesso
                        , ele tem tudo do CTI! É um sagui, e foi selecionado pelo prof. Neivaldo Strutzel
                        Para ser o mascote da Semana do Colégio
                    </span>
                </div>
                <div class="imagem-mp">
                    <img src="">
                </div>
            </div>
            <div class="conteudo-mp">
                <div class="texto-mp">
                    <span class="texto-destaque">Chaveiros pré-prontos<br></span>
                    <span>
                        Temos uma linha de produção com diversos produtos pré-prontos, da maior qualidade 
                        demonstrando artes do mascote do CTI em chaveiros de resina e borracha.
                    </span>
                </div>
                <div class="imagem-mp">
                    <img src="">
                </div>
            </div>
            <div class="conteudo-mp">
                <div class="texto-mp">
                    <span class="texto-destaque">Chaveiros personalizados <br></span>
                    <span>
                        Através da nossa estante na semana no Colégio, você pode fazer o seu próprio produto personalizado!
                         Basta enviar uma foto para nós no nosso email, instagram ou WhatsApp e confeccionaremos seu produto!
                    </span>
                </div>
                <div class="imagem-mp">
                    <img src="">
                </div>
            </div>
        </div>
    </div>
    <div class="centraliza">
        <div class="separador">
            <div class="bola-separador"></div>
        </div>
    </div>
    <div id="container-video">
        <span class="texto-destaque" style="margin-left: 30px;">Como fazemos nossos produtos?</span>
        <div class="conteudo-mp-video">
             <div class="texto-mp">
                <span class="texto-destaque">Nosso método <br></span>
                <span>Através da nossa estante na semana no Colégio, você pode fazer o seu próprio produto personalizado! Basta enviar uma foto para nós no nosso email, instagram ou WhatsApp e confeccionaremos seu produto!
                </span>
            </div>
            <div class="imagem-mp">
                <iframe width="460" height="225" src="https://www.youtube.com/embed/4E9dKkqtUwk?si=gW5Cb0sIY8OblTvW" title="YouTube video player" 
                frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                allowfullscreen></iframe>
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