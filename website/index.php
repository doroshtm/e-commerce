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
        <link rel="stylesheet" href="./styles_header_footer.css">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400;1,9..40,500&display=swap" rel="stylesheet">         
        <link rel="stylesheet" href="styles_paginas.css">
        <script src="./js/home.js"> </script>
        <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
    </head>
    <body>    
        <header id="header">
            <?php
                $isAdmin = isset($_SESSION['user']['isAdmin']) ? $_SESSION['user']['isAdmin'] : false;
                header_pag($isAdmin, "index.php");
            ?>
        </header>
    <div id="content">
        <div class="container-geral">
        <div id="display-produtos">
        <div id="oquemostra">
            <div class="linha direita">
                <div class="container-produtos-flutuantes">
                    <div class ="produto-flutuante">
                        <img src="./imagens/rendercti.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/Renderpython.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/Rendertouro.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/Renderlobo.png">
                    </div>
                </div>
                <div class="container-produtos-flutuantes">
                    <div class ="produto-flutuante">
                        <img src="./imagens/rendercti.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/Renderpython.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/Rendertouro.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/Renderlobo.png">
                    </div>
                </div>
            </div>
            <div class="linha esquerda">
                <div class="container-produtos-flutuantes">
                    <div class ="produto-flutuante">
                        <img src="./imagens/renderglitch.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/Renderjussara.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/Rendereureka.png">
                    </div>
                    <div class="produto-flutuante">
                        <img src="./imagens/gatoCTI.png">
                    </div>
                </div>
                <div class="container-produtos-flutuantes">
                    <div class ="produto-flutuante">
                        <img src="./imagens/renderglitch.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/Renderjussara.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/Rendereureka.png">
                    </div>
                    <div class="produto-flutuante">
                        <img src="./imagens/gatoCTI.png">
                    </div>
                </div>
            </div>
            <div class="linha direita">
                <div class="container-produtos-flutuantes">
                    <div class ="produto-flutuante">
                        <img src="./imagens/info.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/mec.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/elet.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/Emblema_Mascotero.svg">
                    </div>
                </div>
                <div class="container-produtos-flutuantes">
                    <div class ="produto-flutuante">
                        <img src="./imagens/info.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/mec.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/elet.png">
                    </div>
                    <div class ="produto-flutuante">
                        <img src="./imagens/cti.png">
                    </div>
                </div>
            </div>
            <div id="cobertura-vidro">
                 <div id="conteudo-vidro">
                    <div id="texto-vidro">
                        <span> Venha pegar o seu </span>
                        <span id="destaque">mascote da sorte</span>
                        <span id="descricao">
                            Chaveiros de pano e adesivos 
                            com ilustrações originais de mascotes do CTI 
                            para te dar sorte no vestibulinho!
                        </span>
                        <a href="produtos.php">
                            <div id="botao-produto-home">
                                Obtenha já o seu!
                            </div>
                        </a>
                    </div>
                    <div id="imagem-vidro">
                        <img src="./imagens/cti_sticker.png">
                    </div>
                </div> 
            </div>
        </div>
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
                    <iframe width="460" height="225" src="https://www.youtube.com/embed/iPAduZRQrVo?si=7Zmd0PQJGX2Otnwy" title="YouTube video player"
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